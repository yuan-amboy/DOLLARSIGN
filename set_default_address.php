<?php
session_start();
require_once "database.php";

// Check if user is logged in
if (!isset($_SESSION["users"]) || !isset($_SESSION["users"]["email"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["address_id"])) {
    $addressId = (int)$_POST["address_id"];
    $userEmail = $_SESSION["users"]["email"];
    
    // Security check - verify the address belongs to this user
    $checkSql = "SELECT * FROM addresses WHERE id = ? AND user_email = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "is", $addressId, $userEmail);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);
    
    if ($address = mysqli_fetch_assoc($result)) {
        // First, unset all default addresses for this user
        $unsetDefaultSql = "UPDATE addresses SET is_default = 0 WHERE user_email = ?";
        $unsetDefaultStmt = mysqli_prepare($conn, $unsetDefaultSql);
        mysqli_stmt_bind_param($unsetDefaultStmt, "s", $userEmail);
        mysqli_stmt_execute($unsetDefaultStmt);
        
        // Then set the selected address as default
        $setDefaultSql = "UPDATE addresses SET is_default = 1 WHERE id = ?";
        $setDefaultStmt = mysqli_prepare($conn, $setDefaultSql);
        mysqli_stmt_bind_param($setDefaultStmt, "i", $addressId);
        
        if (mysqli_stmt_execute($setDefaultStmt)) {
            // Update the session with the new default address
            $_SESSION['users']['default_address'] = $address['address'] . ', ' . $address['city'] . ', ' . $address['state'] . ' ' . $address['zip'];
            
            $_SESSION['address_message'] = "Default address updated successfully";
            $_SESSION['address_status'] = "success";
        } else {
            $_SESSION['address_message'] = "Error updating default address: " . mysqli_error($conn);
            $_SESSION['address_status'] = "error";
        }
    } else {
        $_SESSION['address_message'] = "Invalid address selected";
        $_SESSION['address_status'] = "error";
    }
    
    header("Location: addresses.php");
    exit();
} else {
    // If accessed directly without POST request
    header("Location: addresses.php");
    exit();
}
?>