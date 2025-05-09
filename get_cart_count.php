<?php
session_start();
require_once "database.php";
header('Content-Type: application/json');

// Default response
$response = ['count' => 0];

// Check if user is logged in
if (isset($_SESSION['users']) && isset($_SESSION['users']['id'])) {
    $userId = $_SESSION['users']['id'];
    
    // Query to get the number of items in cart
    $sql = "SELECT SUM(quantity) AS total_count FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $response['count'] = (int)$row['total_count'];
    }
}

echo json_encode($response);
?>