<?php
session_start();
require_once "database.php";
header('Content-Type: application/json');

// Default response
$response = ['items' => []];

// Check if user is logged in
if (isset($_SESSION['users']) && isset($_SESSION['users']['id'])) {
    $userId = $_SESSION['users']['id'];
    
    // Query to get cart items with product details
    $sql = "SELECT c.id, c.product_id, c.quantity, c.size, p.name, p.price, p.imageFront 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        // Convert price to float for proper JSON encoding
        $row['price'] = (float)$row['price'];
        $response['items'][] = $row;
    }
}

echo json_encode($response);
?>