<?php
// Include database connection
include '../config/connection_db.php';

// Function to delete a product from the bag by ID
function deleteFromBag($id) {
    global $conn;

    // Prepare SQL query to delete a product from the bag by ID
    $sql = "DELETE FROM bag WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute query
    if ($stmt->execute()) {
        return ['status' => 1, 'message' => 'Product deleted from bag successfully'];
    } else {
        return ['status' => 0, 'message' => 'Failed to delete product from bag'];
    }
}

// Check if POST request and ID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Delete product from bag
    $response = deleteFromBag($id);

    // Return response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Return error response for invalid request
    header('Content-Type: application/json');
    echo json_encode(['status' => 0, 'message' => 'Invalid request or missing ID']);
}

// Close connection
$conn->close();
?>