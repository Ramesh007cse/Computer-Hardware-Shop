<?php
$host = "localhost";  // Change if using a different database host
$user = "root";       // Your MySQL username
$pass = "";           // Your MySQL password (default is empty for XAMPP)
$dbname = "computer_shop"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
