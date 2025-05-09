<?php
session_start();
require_once "database.php";
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['users']) || !isset($_SESSION['users']['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get user ID from session
$userId = $_SESSION['users']['id'];

// Check if all required fields are provided
if (!isset($_POST['cart_id']) || !isset($_POST['change'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Sanitize inputs
$cartId = (int)$_POST['cart_id'];
$change = (int)$_POST['change'];

// Verify the cart item belongs to the current user
$checkCartSql = "SELECT id, quantity FROM cart WHERE id = ? AND user_id = ?";
$checkCartStmt = mysqli_prepare($conn, $checkCartSql);
mysqli_stmt_bind_param($checkCartStmt, "ii", $cartId, $userId);
mysqli_stmt_execute($checkCartStmt);
$checkCartResult = mysqli_stmt_get_result($checkCartStmt);

if (mysqli_num_rows($checkCartResult) === 0) {
    echo json_encode(['success' => false, 'message' => 'Cart item not found']);
    exit;
}

$cartItem = mysqli_fetch_assoc($checkCartResult);
$newQuantity = $cartItem['quantity'] + $change;

// If quantity becomes 0 or negative, remove the item
if ($newQuantity <= 0) {
    $deleteSql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteSql);
    mysqli_stmt_bind_param($deleteStmt, "ii", $cartId, $userId);
    
    if (mysqli_stmt_execute($deleteStmt)) {
        echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove item: ' . mysqli_error($conn)]);
    }
} else {
    // Update the quantity
    $updateSql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
    $updateStmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($updateStmt, "iii", $newQuantity, $cartId, $userId);
    
    if (mysqli_stmt_execute($updateStmt)) {
        echo json_encode(['success' => true, 'message' => 'Quantity updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update quantity: ' . mysqli_error($conn)]);
    }
}
?>