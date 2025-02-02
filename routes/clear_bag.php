<?php
// Include database connection
include '../config/connection_db.php';

// Function to clear all products from the bag by userId
function clearBag($userId) {
    global $conn;

    // Prepare SQL query to delete all products from the bag by userId
    $sql = "DELETE FROM bag WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);

    // Execute query
    if ($stmt->execute()) {
        return ['status' => 1, 'message' => 'All products cleared from bag successfully'];
    } else {
        return ['status' => 0, 'message' => 'Failed to clear products from bag'];
    }
}

// Check if POST request and userId is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Clear products from bag
    $response = clearBag($userId);

    // Return response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Return error response for invalid request
    header('Content-Type: application/json');
    echo json_encode(['status' => 0, 'message' => 'Invalid request or missing userId']);
}

// Close connection
$conn->close();
?>