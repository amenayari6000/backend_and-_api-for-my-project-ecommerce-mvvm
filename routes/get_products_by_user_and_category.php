<?php
include '../config/connection_db.php';
header('Content-Type: application/json');

// Debug logging function
function debugLog($type, $message, $data = null) {
    $logFile = '../logs/add_to_bag.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp][$type] $message";
    if ($data) $logEntry .= "\nData: " . json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($logFile, $logEntry . "\n\n", FILE_APPEND);
}

// Get category ID from name
function getCategoryId($categoryName) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0) ? $result->fetch_assoc()['id'] : null;
}

// Verify user exists
function verifyUser($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT userId FROM users WHERE userId = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

// Add to bag function
function addToBag($userId, $title, $price, $description, $category, $image, $imageTwo, $imageThree, $rate, $count, $saleState) {
    global $conn;
    
    debugLog("INFO", "Adding product to bag", [
        'userId' => $userId,
        'title' => $title,
        'category' => $category
    ]);

    // Get category ID
    $categoryId = getCategoryId($category);
    if (!$categoryId) {
        debugLog("ERROR", "Invalid category", ['category' => $category]);
        return ['status' => 0, 'message' => 'Invalid category'];
    }

    // Check for duplicate
    $checkStmt = $conn->prepare("SELECT id FROM bag WHERE userId = ? AND title = ?");
    $checkStmt->bind_param("ss", $userId, $title);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows > 0) {
        debugLog("WARNING", "Product already in bag", ['userId' => $userId, 'title' => $title]);
        return ['status' => 0, 'message' => 'Product already in bag'];
    }

    // Insert into bag
    $sql = "INSERT INTO bag (userId, title, price, description, category_id, image, image_two, image_three, rate, count, sale_state) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdssisssdi", 
            $userId, 
            $title, 
            $price, 
            $description, 
            $categoryId,
            $image, 
            $imageTwo, 
            $imageThree, 
            $rate, 
            $count, 
            $saleState
        );

        if ($stmt->execute()) {
            debugLog("SUCCESS", "Product added successfully", ['userId' => $userId, 'title' => $title]);
            return ['status' => 1, 'message' => 'Product added to bag successfully'];
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        debugLog("ERROR", "Failed to add product", ['error' => $e->getMessage()]);
        return ['status' => 0, 'message' => 'Failed to add product to bag'];
    }
}

// Main execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['userId']) || !verifyUser($_POST['userId'])) {
        echo json_encode(['status' => 0, 'message' => 'Invalid user']);
        exit;
    }

    $required = ['title', 'price', 'description', 'category', 'image', 
                 'image_two', 'image_three', 'rate', 'count', 'sale_state'];
    
    foreach ($required as $field) {
        if (!isset($_POST[$field])) {
            echo json_encode(['status' => 0, 'message' => "Missing field: $field"]);
            exit;
        }
    }

    $response = addToBag(
        $_POST['userId'],
        $_POST['title'],
        (double)$_POST['price'],
        $_POST['description'],
        $_POST['category'],
        $_POST['image'],
        $_POST['image_two'],
        $_POST['image_three'],
        (double)$_POST['rate'],
        (int)$_POST['count'],
        (int)$_POST['sale_state']
    );

    echo json_encode($response);
} else {
    echo json_encode(['status' => 0, 'message' => 'Invalid request method']);
}

$conn->close();
?>