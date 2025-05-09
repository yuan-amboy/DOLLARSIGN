<?php
session_start();
require_once "database.php";

if (!isset($_SESSION["users"]) || !isset($_SESSION["users"]["email"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $addressId = $_GET['id'];
    $userEmail = $_SESSION["users"]["email"];
    
    // First, verify that this address belongs to the current user
    $checkSql = "SELECT * FROM addresses WHERE id = ? AND user_email = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "is", $addressId, $userEmail);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    
    if ($address = mysqli_fetch_assoc($checkResult)) {
        $isDefault = $address['is_default'];
        
        // Delete the address
        $deleteSql = "DELETE FROM addresses WHERE id = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($deleteStmt, "i", $addressId);
        
        if (mysqli_stmt_execute($deleteStmt)) {
            // If the deleted address was the default address, pick a new default
            if ($isDefault) {
                // Get another address to set as default
                $newDefaultSql = "SELECT * FROM addresses WHERE user_email = ? LIMIT 1";
                $newDefaultStmt = mysqli_prepare($conn, $newDefaultSql);
                mysqli_stmt_bind_param($newDefaultStmt, "s", $userEmail);
                mysqli_stmt_execute($newDefaultStmt);
                $newDefaultResult = mysqli_stmt_get_result($newDefaultStmt);
                
                if ($newDefault = mysqli_fetch_assoc($newDefaultResult)) {
                    // Set the new default address
                    $setDefaultSql = "UPDATE addresses SET is_default = 1 WHERE id = ?";
                    $setDefaultStmt = mysqli_prepare($conn, $setDefaultSql);
                    mysqli_stmt_bind_param($setDefaultStmt, "i", $newDefault['id']);
                    mysqli_stmt_execute($setDefaultStmt);
                    
                    // Update session with the new default address
                    $_SESSION['users']['default_address'] = $newDefault['address'] . ', ' . 
                                                           $newDefault['city'] . ', ' . 
                                                           $newDefault['state'] . ' ' . 
                                                           $newDefault['zip'];
                } else {
                    // No more addresses, remove default from session
                    if (isset($_SESSION['users']['default_address'])) {
                        unset($_SESSION['users']['default_address']);
                    }
                }
            }
            
            $_SESSION['address_message'] = "Address deleted successfully";
            $_SESSION['address_status'] = "success";
        } else {
            $_SESSION['address_message'] = "Error deleting address: " . mysqli_error($conn);
            $_SESSION['address_status'] = "error";
        }
    } else {
        $_SESSION['address_message'] = "Address not found or does not belong to you";
        $_SESSION['address_status'] = "error";
    }
} else {
    $_SESSION['address_message'] = "Invalid address ID";
    $_SESSION['address_status'] = "error";
}

header("Location: addresses.php");
exit();
?>