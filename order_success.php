<?php
session_start();

// Check if an order exists in the session
if (!isset($_SESSION['order'])) {
    header("Location: order.php"); // Redirect to home if no order found
    exit();
}

// Retrieve order details from session
$order = $_SESSION['order'];

// Clear session after displaying the confirmation
unset($_SESSION['order']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success">
            <h2>🎉 Order Confirmed!</h2>
            <p>Thank you for your order. Your purchase details are below:</p>
            <ul>
                <li><strong>Product:</strong> <?= htmlspecialchars($order['product']) ?></li>
                <li><strong>Price:</strong> Rs. <?= number_format($order['price']) ?></li>
                <li><strong>Quantity:</strong> <?= htmlspecialchars($order['quantity']) ?></li>
                <li><strong>Total:</strong> Rs. <?= number_format($order['total']) ?></li>
                <li><strong>Phone Number:</strong> <?= htmlspecialchars($order['phone']) ?></li>
            </ul>
            <p>We will contact you soon for further details.</p>
        </div>
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
</body>
</html>
