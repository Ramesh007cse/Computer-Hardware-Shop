<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $country_code = $_POST["country_code"];
    $mobile = $_POST["mobile"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>showError('Passwords do not match.');</script>";
    } else {
        $full_mobile = $country_code . $mobile;
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username already exists in the users table
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>showError('Username already taken.');</script>";
            $stmt->close();
        } else {
            // Check if email already exists in the users table
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo "<script>showError('Email already registered.');</script>";
                $stmt->close();
            } else {
                // Check if mobile number already exists in the users table
                $stmt = $conn->prepare("SELECT id FROM users WHERE mobile = ?");
                $stmt->bind_param("s", $full_mobile);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    echo "<script>showError('Mobile number already registered.');</script>";
                    $stmt->close();
                } else {
                    // All checks passed, insert into the users table
                    $stmt = $conn->prepare("INSERT INTO users (username, email, mobile, password) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $username, $email, $full_mobile, $hashed_password);

                    if ($stmt->execute()) {
                        // Registration successful, redirect to login page
                        header("Location: login.php");
                        exit;
                    } else {
                        echo "<script>showError('Error: " . $stmt->error . "');</script>";
                    }
                    $stmt->close();
                }
            }
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-notification {
            background-color: #f44336;
            color: black;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            display: none;
            font-size: 14px;
            text-align: center;
        }
        body {
            background-image: url(./reglog.png);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Create an Account</h2>
            
            <!-- Error Notification Box -->
            <div id="error-notification" class="error-notification"></div>

            <form method="POST" name="registerForm" onsubmit="return validateForm()">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <small class="error-message" id="username-error"></small>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <small class="error-message" id="email-error"></small>
                </div>
                <div class="input-group">
                    <label for="mobile">Mobile Number:</label>
                    <div class="mobile-container">
                        <select id="country_code" name="country_code" required>
                            <option value="+1">🇺🇸 +1 (USA)</option>
                            <option value="+91" selected>🇮🇳 +91 (India)</option>
                            <option value="+44">🇬🇧 +44 (UK)</option>
                            <option value="+61">🇦🇺 +61 (Australia)</option>
                            <option value="+81">🇯🇵 +81 (Japan)</option>
                        </select>
                        <input type="tel" id="mobile" name="mobile" required maxlength="10" pattern="\d{10}" oninput="validateMobile()" placeholder="Enter 10-digit number">
                    </div>
                    <small id="mobile-error" class="error-message"></small>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <small id="password-error" class="error-message"></small>
                </div>
                <button type="submit" class="btn-submit">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

    <script>
        // Function to show error messages dynamically in notification
        function showError(message) {
            var errorNotification = document.getElementById("error-notification");
            errorNotification.innerHTML = message;
            errorNotification.style.display = "block";

            // Automatically hide the notification after 5 seconds
            setTimeout(function() {
                errorNotification.style.display = "none";
            }, 5000);
        }

        function validateEmail() {
            var emailInput = document.getElementById("email");
            var emailError = document.getElementById("email-error");
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!emailPattern.test(emailInput.value)) {
                emailError.style.display = "block";
                emailError.innerHTML = "Please enter a valid email address.";
                return false;
            } else {
                emailError.style.display = "none";
                return true;
            }
        }

        function validateMobile() {
            var mobileInput = document.getElementById("mobile");
            var errorMessage = document.getElementById("mobile-error");
            var countryCode = document.getElementById("country_code").value;
            var mobilePattern;

            switch (countryCode) {
                case "+91": // India
                    mobilePattern = /^[6-9][0-9]{9}$/;
                    break;
                case "+1": // USA
                    mobilePattern = /^[2-9]{1}[0-9]{9}$/;
                    break;
                case "+44": // UK
                    mobilePattern = /^07[0-9]{9}$/;
                    break;
                case "+61": // Australia
                    mobilePattern = /^04[0-9]{8}$/;
                    break;
                case "+81": // Japan
                    mobilePattern = /^[0-9]{10}$/;
                    break;
                default:
                    mobilePattern = /^[0-9]{10}$/; // Default pattern for a generic 10-digit number
                    break;
            }

            if (!mobilePattern.test(mobileInput.value)) {
                errorMessage.style.display = "block";
                errorMessage.innerHTML = "Please enter a valid mobile number for this country.";
                return false;
            } else {
                errorMessage.style.display = "none";
                return true;
            }
        }

        function validatePasswordStrength() {
            var password = document.getElementById("password").value;
            var passwordError = document.getElementById("password-error");

            var regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;  // At least 8 characters, letters, and numbers
            if (!regex.test(password)) {
                passwordError.innerHTML = "Password must be at least 8 characters long, and include both letters and numbers.";
                passwordError.style.display = "block";
                return false;
            }
            passwordError.style.display = "none";
            return true;
        }

        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var passwordError = document.getElementById("password-error");
            var username = document.getElementById("username").value;
            var usernameError = document.getElementById("username-error");

            // Reset all error messages
            usernameError.style.display = "none";
            passwordError.style.display = "none";

            // Check if passwords match
            if (password !== confirmPassword) {
                passwordError.innerHTML = "Passwords do not match.";
                passwordError.style.display = "block";
                return false;
            }

            // Validate Email
            if (!validateEmail()) {
                return false;
            }

            // Validate Mobile Number
            if (!validateMobile()) {
                return false;
            }

            // Validate Password Strength
            if (!validatePasswordStrength()) {
                return false;
            }

            return true;
        }
    </script>
</body>
</html>