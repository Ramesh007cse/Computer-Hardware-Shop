<?php
session_start();
require 'db.php';  // Include the database connection

$product_name = "16GB DDR4 GAMING RAM"; // Updated product name
$price = 8000; // Adjusted price for the RAM

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store the order information in the session
    $_SESSION['order'] = [
        'product' => $product_name,
        'price' => $price,
        'total' => $price
    ];

    // Insert product into the database
    if ($stmt = $conn->prepare("INSERT INTO products (product_name, price) VALUES (?, ?)")) {
        $stmt->bind_param("sd", $product_name, $price);  // 's' for string, 'd' for decimal (float)
        
        if ($stmt->execute()) {
            // Redirect to the order page if the product is inserted successfully
            header("Location: order.php");
            exit();
        } else {
            // Handle error during execution
            echo "Error executing query: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Handle error when preparing the statement
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .container { margin-top: 50px; }
        .product-image { width: 100%; max-width: 550px; border-radius: 10px; transition: transform 0.3s, box-shadow 0.3s; }
        .product-image:hover { transform: scale(1.1); box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3); }
        .price { font-size: 24px; font-weight: bold; color: #28a745; }
        .specs-table td { padding: 10px; border-bottom: 1px solid #ddd; }
        .btn-custom { font-size: 16px; padding: 10px 20px; }
    </style>
</head>
<body>

<header class="bg-dark text-white text-center py-3">
    <h1 class="m-0">Computer Hardware Shop</h1>
</header>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="ram.jpg" class="product-image img-fluid" alt="16GB RAM"> <!-- Updated image source -->
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($product_name) ?></h2>
            <p class="text-muted">Brand: <strong>GAMING</strong></p>
            <p class="price">Rs. <?= number_format($price) ?></p>
            <p><strong>In Stock</strong> - Ships within 24 hours</p>

            <form method="post">
                <button type="submit" class="btn btn-primary btn-custom">Order Now</button>
            </form>

            <table class="table mt-4 specs-table">
                <tr><td><strong>Capacity:</strong></td><td>16GB</td></tr>
                <tr><td><strong>Type:</strong></td><td>DDR4 GAMING</td></tr>
                <tr><td><strong>Speed:</strong></td><td>3200 MHz</td></tr>
                <tr><td><strong>Form Factor:</strong></td><td>DIMM</td></tr>
                <tr><td><strong>Voltage:</strong></td><td>1.35V</td></tr>
                <tr><td><strong>Warranty:</strong></td><td>10YEAR</td></tr>
            </table>
        </div>
    </div>

    <div class="mt-5">
        <h3>Product Description</h3>
        <p>This 16GB DDR4 GAMING RAM is designed for high-performance gaming, multitasking, and content creation. With a speed of 3200 MHz and 1.35V operating voltage, it offers smooth and efficient performance for modern applications and games.</p>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p>Contact us at: ramesh@hardwarestore.com | Call: +91 9360656401</p>
    <p>&copy; Computer Hardware Shop. All rights reserved.</p>
</footer>

</body>
</html>
