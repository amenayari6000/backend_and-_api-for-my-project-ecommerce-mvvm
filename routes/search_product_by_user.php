<?php
// Include database connection
include '../config/connection_db.php';
header('Content-Type: application/json');

// Base URL for images
$baseUrl = "http://192.168.100.8/ecommerce_api/images/";

// Function to search for products by query
function searchProduct($query) {
    global $conn, $baseUrl;

    // Prepare SQL query to search for products by query
    $sql = "SELECT p.*, c.name as category FROM products p JOIN categories c ON p.category_id = c.id WHERE p.title LIKE ? OR p.description LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeQuery = '%' . $query . '%';
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Ensure category is non-null
        $row['category'] = $row['category'] ?? 'Unknown';
        
        // Add base URL to image paths
        $row['image'] = $baseUrl . $row['image'];
        $row['image_two'] = $baseUrl . $row['image_two'];
        $row['image_three'] = $baseUrl . $row['image_three'];
        
        $products[] = $row;
    }

    return $products;
}

// Check if POST request and required fields are provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['query'])) {
    $query = $_POST['query'];

    // Search for products
    $products = searchProduct($query);

    // Return products as JSON
    echo json_encode($products);
} else {
    // Return error response for invalid request
    echo json_encode(['status' => 0, 'message' => 'Invalid request or missing fields']);
}

// Close connection
$conn->close();
?>