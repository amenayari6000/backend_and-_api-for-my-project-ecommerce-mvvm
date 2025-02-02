<?php
include '../config/connection_db.php';
header('Content-Type: application/json');

function getBagProductsByUser($userId) {
    global $conn;
    
    // Base URL for images
    $baseUrl = "http://192.168.100.8/ecommerce_api/images/";

    try {
        $sql = "SELECT b.id, b.title, b.price, b.description, 
                       c.name as category, 
                       CONCAT('$baseUrl', b.image) as image,
                        CONCAT('$baseUrl', b.image) as image_two,
                          CONCAT('$baseUrl', b.image) as image_three,
                       
                       b.rate, b.count, b.sale_state
                FROM bag b
                JOIN categories c ON b.category_id = c.id
                WHERE b.userId = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return $products;
    } catch (Exception $e) {
        error_log("Error fetching bag products: " . $e->getMessage());
        return [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['userId'])) {
        echo json_encode([]);
        exit;
    }

    $products = getBagProductsByUser($_POST['userId']);
    echo json_encode($products);
} else {
    echo json_encode([]);
}

$conn->close();
?>