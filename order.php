<?php
session_start();
session_regenerate_id(true); // Secure session management

require 'db.php'; // Database connection
require 'vendor/autoload.php'; // Twilio SDK

use Twilio\Rest\Client;

// Fetch last inserted product
$last_product_query = "SELECT id, product_name, price FROM products ORDER BY id DESC LIMIT 1";
$last_product_result = $conn->query($last_product_query);
$last_product = ($last_product_result->num_rows > 0) ? $last_product_result->fetch_assoc() : null;

// Twilio Credentials
$sid = "ACbc82777c5e24be677292dddb98360d38";
$token = "07198bab5bd8d6d26afdac25ede19527";
$verify_sid = "VA86740887492a492d4ea7ffb506310f0c";
$twilio = new Client($sid, $token);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["order"])) {
    $name = filter_var($_POST["user_name"], FILTER_SANITIZE_STRING);
    $quantity = filter_var($_POST["quantity"], FILTER_VALIDATE_INT);
    $user_phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $product_id = filter_var($_POST["product_id"], FILTER_VALIDATE_INT);

    if ($last_product && $product_id == $last_product['id'] && $quantity > 0 && preg_match("/^\+91[6-9]\d{9}$/", $user_phone) && !empty($name)) {
        $product_name = $last_product['product_name'];
        $price = $last_product['price'];
        $total = $price * $quantity;

        // Store order details in session
        $_SESSION['order'] = [
            'user_name' => $name,
            'product' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
            'total' => $total,
            'phone' => $user_phone
        ];

        // Request OTP using Twilio
        try {
            $twilio->verify->v2->services($verify_sid)
                ->verifications
                ->create($user_phone, "sms");

            // Insert Order into Database
            $query = "INSERT INTO mobile_orders (product_name, price, quantity, total, user_phone, user_name, otp_expiry)
                      VALUES (?, ?, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 5 MINUTE))";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("siisss", $product_name, $price, $quantity, $total, $user_phone, $name);

            if ($stmt->execute()) {
                header("Location: otp_verification.php");
                exit();
            } else {
                $error = "Failed to place order. Please try again.";
            }
        } catch (Exception $e) {
            $error = "Error sending OTP: " . $e->getMessage();
        }
    } else {
        $error = "Please enter a valid name, mobile number (India only), and quantity.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: rgba(32, 190, 127, 0.75);
            color: #333;
        }
        .container {
            max-width: 500px;
            margin: auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(255, 0, 195, 0.97);
        }
        h2 {
            font-size: 28px;
            text-align: center;
            font-weight: bold;
            color: #2b2d42;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 15px;
        }
        .btn-primary {
            background-color: #2b2d42;
            border: none;
            color: #fff;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1f1f2e;
        }
        .text-danger {
            text-align: center;
            font-weight: 500;
            color: #ff6347;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
        }
        #loading {
            display: none;
            text-align: center;
            padding-top: 30px;
        }
        .form-label {
            font-weight: 600;
            color: #2b2d42;
        }
    </style>
    <script>
        function updateTotal() {
            var price = <?= $last_product ? $last_product['price'] : 0 ?>;
            var quantity = document.getElementById('quantity').value;
            document.getElementById('total').value = 'Rs. ' + (price * quantity).toLocaleString();
        }

        window.onload = function() {
            updateTotal(); // Set default total price for quantity = 1
        };

        function showLoader() {
            document.getElementById("loading").style.display = "block";
            document.getElementById("orderForm").style.display = "none";
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Order Product</h2>

        <form method="post" id="orderForm" onsubmit="showLoader()">
            <div class="mb-3">
                <label for="product" class="form-label">Choose Product:</label>
                <select id="product_id" name="product_id" class="form-control" required onchange="updateTotal()">
                    <?php if ($last_product): ?>
                        <option value="<?= $last_product['id'] ?>" data-price="<?= $last_product['price'] ?>">
                            <?= htmlspecialchars($last_product['product_name']) ?> - Rs. <?= number_format($last_product['price']) ?>
                        </option>
                    <?php else: ?>
                        <option value="">No products available</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="user_name" class="form-label">Full Name:</label>
                <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Enter your full name" required>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" required oninput="updateTotal()">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Mobile Number (with +91):</label>
                <input type="text" id="phone" name="phone" class="form-control" placeholder="+91XXXXXXXXXX" required>
            </div>

            <div class="mb-3">
                <label for="total" class="form-label">Total Price:</label>
                <input type="text" id="total" class="form-control" readonly>
            </div>

            <?php if (!empty($error)) { echo "<p class='text-danger'>$error</p>"; } ?>

            <button type="submit" name="order" class="btn btn-primary">Order Now</button>
        </form>

        <div id="loading">
            <div class="spinner-border"></div>
            <p>Sending OTP... Please wait.</p>
        </div>
    </div>
</body>
</html>
