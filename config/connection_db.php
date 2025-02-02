<?php
// Database credentials
$host = "localhost"; 
$username = "root";
$password = "";
$dbname = "ecommerce_db";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection error. Please contact support.");
}

// Set character set to UTF-8
$conn->set_charset("utf8");
?>
