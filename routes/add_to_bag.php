<?php
include '../config/connection_db.php';
header('Content-Type: application/json');

// Add fetchCategoryId function
function fetchCategoryId($category) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    return null;
}

function getImageName($imageUrl) {
    $baseUrl = "http://192.168.100.8/ecommerce_api/images/";
    if (strpos($imageUrl, $baseUrl) === 0) {
        return basename($imageUrl);
    }
    return $imageUrl;
}

function addToBag($userId, $title, $price, $description, $category, $image, $imageTwo, $imageThree, $rate, $count, $saleState) {
    global $conn;
    
    // Get category ID
    $categoryId = fetchCategoryId($category);
    if (!$categoryId) {
        return ['status' => 0, 'message' => 'Invalid category'];
    }

    // Process images
    $imageName = getImageName($image);
    $imageTwoName = getImageName($imageTwo);
    $imageThreeName = getImageName($imageThree);

  
    try {
        // Start transaction
        $conn->begin_transaction();

        // Check for existing item
        $checkStmt = $conn->prepare("SELECT id, count FROM bag WHERE userId = ? AND title = ?");
        $checkStmt->bind_param("ss", $userId, $title);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Product already exists, update count
            $row = $result->fetch_assoc();
            $newCount = $row['count'] + $count;
            
            $updateStmt = $conn->prepare("UPDATE bag SET count = ? WHERE id = ?");
            $updateStmt->bind_param("ii", $newCount, $row['id']);
            
            if (!$updateStmt->execute()) {
                throw new Exception("Failed to update count");
            }
            
            $conn->commit();
            return [
                'status' => 1,
                'message' => 'Product quantity updated in bag',
                'count' => $newCount
            ];
       
        }
    
        // Insert new item
        $sql = "INSERT INTO bag (userId, title, price, description, category_id, image, image_two, image_three, rate, count, sale_state) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssdsississi", 
            $userId, 
            $title, 
            $price, 
            $description, 
            $categoryId,
            $imageName,
            $imageTwoName,
            $imageThreeName,
            $rate, 
            $count, 
            $saleState
        );
    
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
    
        // Commit transaction if everything is successful
        $conn->commit();
        return ['status' => 1, 'message' => 'Product added to bag successfully'];
    
    } catch (Exception $e) {
        // Roll back transaction on error
        $conn->rollback();
        error_log("Error: " . $e->getMessage());
        return ['status' => 0, 'message' => 'Failed to add product'];
    }
}



// Main execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['userId'])) {
        echo json_encode(['status' => 0, 'message' => 'Missing userId']);
        exit;
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
    exit;
}

echo json_encode(['status' => 0, 'message' => 'Invalid request method']);
?>