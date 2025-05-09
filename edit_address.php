<?php
session_start();
require_once "database.php";

if (!isset($_SESSION["users"]) || !isset($_SESSION["users"]["email"])) {
    header("Location: login.php");
    exit();
}

$addressId = isset($_GET['id']) ? $_GET['id'] : null;
$userEmail = $_SESSION["users"]["email"];
$address = null;

if ($addressId && is_numeric($addressId)) {
    $sql = "SELECT * FROM addresses WHERE id = ? AND user_email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $addressId, $userEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $address = mysqli_fetch_assoc($result);
    
    if (!$address) {
        $_SESSION['address_message'] = "Address not found or does not belong to you";
        $_SESSION['address_status'] = "error";
        header("Location: addresses.php");
        exit();
    }
} else {
    $_SESSION['address_message'] = "Invalid address ID";
    $_SESSION['address_status'] = "error";
    header("Location: addresses.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $firstName = isset($_POST["First_Name"]) ? htmlspecialchars(strip_tags(trim($_POST["First_Name"]))) : '';
    $lastName = isset($_POST["Last_Name"]) ? htmlspecialchars(strip_tags(trim($_POST["Last_Name"]))) : '';
    $addressLine = isset($_POST["address"]) ? htmlspecialchars(strip_tags(trim($_POST["address"]))) : '';
    $city = isset($_POST["city"]) ? htmlspecialchars(strip_tags(trim($_POST["city"]))) : '';
    $zip = isset($_POST["zip"]) ? htmlspecialchars(strip_tags(trim($_POST["zip"]))) : '';
    $state = isset($_POST["state"]) ? htmlspecialchars(strip_tags(trim($_POST["state"]))) : '';
    $phone = isset($_POST["phone"]) ? htmlspecialchars(strip_tags(trim($_POST["phone"]))) : '';
    $isDefault = isset($_POST["is_default"]) ? 1 : ($address['is_default'] ? 1 : 0); // Keep as default if it was, unless changed
    
    // Check if all required fields are filled
    if (empty($firstName) || empty($lastName) || empty($addressLine) || empty($city) || empty($zip) || empty($state) || empty($phone)) {
        $_SESSION['address_message'] = "All fields are required";
        $_SESSION['address_status'] = "error";
    } else {
        // If setting as default, unset all other default addresses
        if ($isDefault && !$address['is_default']) {
            $unsetDefaultSql = "UPDATE addresses SET is_default = 0 WHERE user_email = ?";
            $unsetDefaultStmt = mysqli_prepare($conn, $unsetDefaultSql);
            mysqli_stmt_bind_param($unsetDefaultStmt, "s", $userEmail);
            mysqli_stmt_execute($unsetDefaultStmt);
        }
        
        // Update the address
        $updateSql = "UPDATE addresses SET First_Name = ?, Last_Name = ?, address = ?, city = ?, state = ?, zip = ?, phone = ?, is_default = ? WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "sssssssii", $firstName, $lastName, $addressLine, $city, $state, $zip, $phone, $isDefault, $addressId);
        
        if (mysqli_stmt_execute($updateStmt)) {
            $_SESSION['address_message'] = "Address updated successfully";
            $_SESSION['address_status'] = "success";
            
            // Update session with default address if this is set as default
            if ($isDefault) {
                $_SESSION['users']['default_address'] = $addressLine . ', ' . $city . ', ' . $state . ' ' . $zip;
            }
            
            header("Location: addresses.php");
            exit();
        } else {
            $_SESSION['address_message'] = "Error updating address: " . mysqli_error($conn);
            $_SESSION['address_status'] = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Address | DOLLARSIGN</title> <link rel="stylesheet" href="main.css">
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php include("navbar.php"); ?>
    
    <div class="edit-address-container">
        <h2>Edit Address</h2>
    
    <?php if (isset($_SESSION['address_message'])): ?>
        <div class="address-message <?php echo $_SESSION['address_status'] ?? 'success'; ?>">
            <?php echo htmlspecialchars($_SESSION['address_message']); ?>
        </div>
        <?php unset($_SESSION['address_message']); unset($_SESSION['address_status']); ?>
    <?php endif; ?>
    
    <form action="edit_address.php?id=<?php echo $addressId; ?>" method="POST">
        <label>First Name: <input type="text" name="First_Name" value="<?php echo htmlspecialchars($address['First_Name'] ?? ''); ?>" required></label>
        <label>Last Name: <input type="text" name="Last_Name" value="<?php echo htmlspecialchars($address['Last_Name'] ?? ''); ?>" required></label>
        <label>Address: <input type="text" name="address" value="<?php echo htmlspecialchars($address['address'] ?? ''); ?>" required></label>
        <label>City: <input type="text" name="city" value="<?php echo htmlspecialchars($address['city'] ?? ''); ?>" required></label>
        <label>ZIP Code: <input type="text" name="zip" value="<?php echo htmlspecialchars($address['zip'] ?? ''); ?>" required></label>
        <label>Country: <input type="text" value="Philippines" readonly></label>
        <label>State/Province: <input type="text" name="state" value="<?php echo htmlspecialchars($address['state'] ?? ''); ?>" required></label>
        <label>Phone Number: <input type="text" name="phone" value="<?php echo htmlspecialchars($address['phone'] ?? ''); ?>" required></label>
        <label><input type="checkbox" name="is_default" <?php echo $address['is_default'] ? 'checked' : ''; ?>> Set as default address</label>

        <button type="submit" class="btn">Update Address</button>
        <a href="addresses.php" class="btn">Cancel</a>
    </form>
</div>

<style>
    .edit-address-container {
        max-width: 500px;
        margin: 95px auto;
        padding: 40px;
        background-color: rgb(216, 216, 216);
        font-family: Arial, sans-serif;
    }

    .edit-address-container h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .edit-address-container form label {
        display: block;
        margin: 15px 0;
        font-weight: bold;
        text-align: left;
    }

    .edit-address-container form input {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ccc;
    }

    .edit-address-container form input[type="checkbox"] {
        width: auto;
        margin-right: 10px;
    }

    .btn {
        display: inline-block;
        margin: 10px 5px;
        padding: 12px 24px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        background: #000;
        border: none;
        cursor: pointer;
        transition: background 0.3s;
        text-decoration: none;
    }

    .btn:hover {
        background: #222;
    }

    .address-message {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
    }

    .address-message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .address-message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<?php include("footer.php"); ?>
</body>
</html>