<?php
// filepath: /c:/xampp/htdocs/ecommerce_api/routes/get_sale_products_by_user.php

// Include database connection
include '../config/connection_db.php';

// Set content type to JSON
header('Content-Type: application/json');

// Function to insert userId into users table if it does not exist
function insertUserIfNotExists($userId) {
    global $conn;

    // Prepare SQL query to insert userId if it does not exist
    $sql = "INSERT INTO users (userId) VALUES (?) ON DUPLICATE KEY UPDATE userId = userId";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);

    // Execute query and check for errors
    if ($stmt->execute()) {
        return true;
    } else {
        return $stmt->error;
    }
}

// Function to fetch sale products with category names
function fetchSaleProducts() {
    global $conn;

// Base URL for images
    $baseUrl = "http://192.168.100.8/ecommerce_api/images/";
    // Prepare SQL query to fetch sale products with category names
    $sql = "SELECT p.id, c.name as category, p.count, p.description, CONCAT('$baseUrl', p.image) as image, 
                   CONCAT('$baseUrl', p.image_two) as image_two, 
                   CONCAT('$baseUrl', p.image_three) as image_three , p.price, p.rate, p.title, p.sale_state 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.sale_state = 1";
    $result = $conn->query($sql);

    // Initialize an array to store fetched products
    $products = [];

    // Loop through the result and append products to the array
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    return $products;
}

// Check if POST request and userId is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Insert userId into users table if it does not exist
    $insertResult = insertUserIfNotExists($userId);
    if ($insertResult === true) {
        // Fetch sale products
        $products = fetchSaleProducts();

        // Return products as JSON
        echo json_encode($products);
    } else {
        // Return error response for failed user insertion
        echo json_encode(['status' => 0, 'message' => 'Failed to insert user', 'details' => $insertResult]);
    }
} else {
    // Return error response for invalid request
    echo json_encode(['status' => 0, 'message' => 'Invalid request or missing userId']);
}

// Close connection
$conn->close();
?>