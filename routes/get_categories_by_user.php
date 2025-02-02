<?php
// filepath: /c:/xampp/htdocs/ecommerce_api/routes/get_categories_by_user.php

// Include database connection
include '../config/connection_db.php';

// Set content type to JSON
header('Content-Type: application/json');

// Function to fetch all category names
function fetchAllCategoryNames() {
    global $conn;

    // Prepare SQL query to fetch all category names
    $sql = "SELECT name FROM categories";
    $result = $conn->query($sql);

    // Initialize an array to store fetched category names
    $categories = [];

    // Loop through the result and append category names to the array
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['name'];
    }

    return $categories;
}

// Check if POST request and userId is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Fetch all category names
    $categories = fetchAllCategoryNames();

    // Return category names as JSON
    echo json_encode($categories);
} else {
    // Return error response for invalid request
    echo json_encode(['status' => 0, 'message' => 'Invalid request or missing userId']);
}

// Close connection
$conn->close();
?>