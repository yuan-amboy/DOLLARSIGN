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
if (!isset($_POST['product_id']) || !isset($_POST['size']) || !isset($_POST['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Sanitize inputs
$productId = (int)$_POST['product_id'];
$size = mysqli_real_escape_string($conn, $_POST['size']);
$quantity = (int)$_POST['quantity'];

// Validate inputs
if ($productId <= 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity']);
    exit;
}

// Check if product exists
$checkProductSql = "SELECT id FROM products WHERE id = ?";
$checkProductStmt = mysqli_prepare($conn, $checkProductSql);
mysqli_stmt_bind_param($checkProductStmt, "i", $productId);
mysqli_stmt_execute($checkProductStmt);
$checkProductResult = mysqli_stmt_get_result($checkProductStmt);

if (mysqli_num_rows($checkProductResult) === 0) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Check if the item already exists in the cart
$checkCartSql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?";
$checkCartStmt = mysqli_prepare($conn, $checkCartSql);
mysqli_stmt_bind_param($checkCartStmt, "iis", $userId, $productId, $size);
mysqli_stmt_execute($checkCartStmt);
$checkCartResult = mysqli_stmt_get_result($checkCartStmt);

if (mysqli_num_rows($checkCartResult) > 0) {
    // Update existing cart item
    $cartItem = mysqli_fetch_assoc($checkCartResult);
    $newQuantity = $cartItem['quantity'] + $quantity;
    
    $updateCartSql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $updateCartStmt = mysqli_prepare($conn, $updateCartSql);
    mysqli_stmt_bind_param($updateCartStmt, "ii", $newQuantity, $cartItem['id']);
    
    if (mysqli_stmt_execute($updateCartStmt)) {
        echo json_encode(['success' => true, 'message' => 'Cart updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update cart: ' . mysqli_error($conn)]);
    }
} else {
    // Add new item to cart
    $addToCartSql = "INSERT INTO cart (user_id, product_id, quantity, size) VALUES (?, ?, ?, ?)";
    $addToCartStmt = mysqli_prepare($conn, $addToCartSql);
    mysqli_stmt_bind_param($addToCartStmt, "iiis", $userId, $productId, $quantity, $size);
    
    if (mysqli_stmt_execute($addToCartStmt)) {
        echo json_encode(['success' => true, 'message' => 'Item added to cart successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add item to cart: ' . mysqli_error($conn)]);
    }
}
?>