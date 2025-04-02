<?php
session_start();
require 'db.php';  
require 'vendor/autoload.php'; 

use Twilio\Rest\Client;

$sid    = "ACbc82777c5e24be677292dddb98360d38";
$token  = "07198bab5bd8d6d26afdac25ede19527";
$verify_sid = "VA86740887492a492d4ea7ffb506310f0c";
$twilio = new Client($sid, $token);

$recaptcha_secret = "YOUR_GOOGLE_RECAPTCHA_SECRET_KEY";

if (!isset($_SESSION['order']['phone'])) {
    die("Error: No phone number found in session.");
}

$user_phone = $_SESSION['order']['phone'];
$error = "";
$message = "";

$query = "SELECT otp_expiry FROM mobile_orders WHERE user_phone = ? AND otp_expiry > NOW()";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_phone);
$stmt->execute();
$stmt->bind_result($otp_expiry);
$stmt->fetch();
$stmt->close();

if ($otp_expiry) {
    $expiry_time = new DateTime($otp_expiry);
    $current_time = new DateTime();
} else {
    $error = "OTP has expired or is invalid. Please request a new one.";
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verify"])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token mismatch.");
    }

    $otp_code = trim($_POST["otp"]);

    if (!empty($otp_code) && ctype_digit($otp_code) && strlen($otp_code) === 6) {
        try {
            $verification_check = $twilio->verify->v2->services($verify_sid)
                ->verificationChecks
                ->create(["to" => $user_phone, "code" => $otp_code]);

            if ($verification_check->status === "approved") {
                $query = "UPDATE mobile_orders SET otp = ?, otp_expiry = NULL WHERE user_phone = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ss", $otp_code, $user_phone);
                $stmt->execute();

                header("Location: order_success.php");
                exit();
            } else {
                $error = "Invalid OTP. Please try again.";
            }
        } catch (Exception $e) {
            $error = "Error verifying OTP: " . $e->getMessage();
        }
    } else {
        $error = "Please enter a valid 6-digit OTP.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["resend"])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token mismatch.");
    }

    try {
        // Always allow resending the OTP regardless of the last request time
        $twilio->verify->v2->services($verify_sid)->verifications->create($user_phone, "sms");

        $query = "UPDATE mobile_orders SET otp_sent_at = NOW(), otp_expiry = DATE_ADD(NOW(), INTERVAL 1 MINUTE) WHERE user_phone = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_phone);
        $stmt->execute();

        $message = "A new OTP has been sent.";
    } catch (Exception $e) {
        $error = "Error resending OTP: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js?render=YOUR_GOOGLE_RECAPTCHA_SITE_KEY"></script>

    <script>
        window.onload = function() {
            // No timer needed, so no JavaScript for countdown
        };
    </script>

    <style>
        body {
            background: aquamarine;
            background-size: cover;
            animation: backgroundAnimation 60s linear infinite;
        }
        @keyframes backgroundAnimation {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
        }
        .otp-input {
            text-align: center;
            font-size: 1.5rem;
            letter-spacing: 10px;
            width: 100%;
            transition: transform 0.3s ease-in-out;
            border: 2px solid #007bff;
            border-radius: 8px;
        }
        .otp-input:focus {
            transform: scale(1.05);
            border-color: #28a745;
            box-shadow: 0px 0px 10px rgba(40, 167, 69, 0.5);
        }
        .btn {
            transition: all 0.3s ease-in-out;
        }
        .btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center text-primary">Verify Your Order</h2>
        <p class="text-center">OTP sent to <strong><?= htmlspecialchars($user_phone) ?></strong></p>

        <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <?php if ($message) echo "<div class='alert alert-success'>$message</div>"; ?>

        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="text" id="otp" name="otp" class="form-control otp-input mb-3" maxlength="6" required>
            <button type="submit" id="verifyButton" name="verify" class="btn btn-success w-100">Verify OTP</button>
            <button type="submit" id="resendButton" name="resend" class="btn btn-secondary w-100 mt-2">Resend OTP</button>
        </form>
    </div>
</body>
</html>
