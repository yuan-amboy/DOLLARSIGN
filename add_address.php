<?php
session_start();
require_once "database.php";

// Debug session at entry point
error_log("Session at start of add_address.php: " . print_r($_SESSION, true));

// Check if user is logged in - FIXED to check for Email (uppercase)
if (!isset($_SESSION["users"]) || !isset($_SESSION["users"]["Email"])) {
    error_log("User not logged in, redirecting to login.php");
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $firstName = isset($_POST["First_Name"]) ? htmlspecialchars(strip_tags(trim($_POST["First_Name"]))) : '';
    $lastName = isset($_POST["Last_Name"]) ? htmlspecialchars(strip_tags(trim($_POST["Last_Name"]))) : '';
    $address = isset($_POST["address"]) ? htmlspecialchars(strip_tags(trim($_POST["address"]))) : '';
    $city = isset($_POST["city"]) ? htmlspecialchars(strip_tags(trim($_POST["city"]))) : '';
    $zip = isset($_POST["zip"]) ? htmlspecialchars(strip_tags(trim($_POST["zip"]))) : '';
    $state = isset($_POST["state"]) ? htmlspecialchars(strip_tags(trim($_POST["state"]))) : '';
    $phone = isset($_POST["phone"]) ? htmlspecialchars(strip_tags(trim($_POST["phone"]))) : '';
    $isDefault = isset($_POST["is_default"]) ? 1 : 0;
    $userEmail = $_SESSION["users"]["Email"];  // FIXED to use Email (uppercase)
    
    error_log("Processing address for user: $userEmail");
    
    // Check if all required fields are filled
    if (empty($firstName) || empty($lastName) || empty($address) || empty($city) || empty($zip) || empty($state) || empty($phone)) {
        $_SESSION['address_message'] = "All fields are required";
        $_SESSION['address_status'] = "error";
        header("Location: addresses.php");
        exit();
    }
    
    // Check if this is the first address (should be default automatically)
    $checkSql = "SELECT COUNT(*) AS address_count FROM addresses WHERE user_email = ?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "s", $userEmail);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    $addressCount = mysqli_fetch_assoc($checkResult)['address_count'];
    
    if ($addressCount == 0) {
        $isDefault = 1; // Make first address default automatically
    }
    
    // If setting as default, unset all other default addresses
    if ($isDefault) {
        $unsetDefaultSql = "UPDATE addresses SET is_default = 0 WHERE user_email = ?";
        $unsetDefaultStmt = mysqli_prepare($conn, $unsetDefaultSql);
        mysqli_stmt_bind_param($unsetDefaultStmt, "s", $userEmail);
        mysqli_stmt_execute($unsetDefaultStmt);
    }
    
    // Insert the new address
    $sql = "INSERT INTO addresses (user_email, First_Name, Last_Name, address, city, state, zip, phone, is_default) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssi", $userEmail, $firstName, $lastName, $address, $city, $state, $zip, $phone, $isDefault);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['address_message'] = "Address added successfully";
        $_SESSION['address_status'] = "success";
        
        // Update session with default address if this is set as default
        if ($isDefault) {
            $_SESSION['users']['default_address'] = $address . ', ' . $city . ', ' . $state . ' ' . $zip;
        }
        
        error_log("Address added successfully");
    } else {
        $_SESSION['address_message'] = "Error adding address: " . mysqli_error($conn);
        $_SESSION['address_status'] = "error";
        error_log("Error adding address: " . mysqli_error($conn));
    }
    
    // Save session before redirect
    session_write_close();
    header("Location: addresses.php");
    exit();
} else {
    // If accessed directly without POST request
    header("Location: addresses.php");
    exit();
}
?>