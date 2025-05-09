<?php
session_start();
require_once "database.php"; // Add this line to include database connection

if (empty($_SESSION['users']['id'])) {
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: login.php?redirect=$redirect_url");
    exit();
}

// Initialize database connection
$conn = $conn ?? null; // Use existing connection or initialize as null

// Set user email from session - note the case must match how it's set in login.php
$userEmail = $_SESSION['users']['email'] ?? ''; // Changed from "Email" to "email" to match login.php
error_log("User email: $userEmail");

// Fetch addresses from database
$addresses = [];
if ($conn) {
    $sql = "SELECT * FROM addresses WHERE user_email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $addresses[] = $row;
        }
    } else {
        error_log("Failed to prepare statement: " . mysqli_error($conn));
    }
} else {
    error_log("Database connection failed");
}

error_log("Found " . count($addresses) . " addresses for user");

// Display message if there is one
$message = '';
$messageClass = '';
if (isset($_SESSION['address_message'])) {
    $message = $_SESSION['address_message'];
    $messageClass = $_SESSION['address_status'] == 'success' ? 'success-message' : 'error-message';
    
    // Clear the message after displaying
    unset($_SESSION['address_message']);
    unset($_SESSION['address_status']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Addresses | DOLLARSIGN</title>
    <link rel="stylesheet" href="main.css">
    <script defer src="script.js"></script>
</head>
<body>
    <?php include("navbar.php"); ?>

    <div class="address-container">
        <h2>My Addresses</h2>
        
        <?php if (!empty($message)): ?>
            <div class="<?php echo $messageClass; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <button id="addAddressBtn" class="btn">Add a New Address</button>
        
        <div id="addressForm" class="address-form hidden">
            <form action="add_address.php" method="POST">
                <label>First Name: <input type="text" name="First_Name" required value="<?php echo htmlspecialchars($_SESSION['users']['First_Name'] ?? ''); ?>"></label>
                <label>Last Name: <input type="text" name="Last_Name" required value="<?php echo htmlspecialchars($_SESSION['users']['Last_Name'] ?? ''); ?>"></label>
                <label>Address: <input type="text" name="address" required></label>
                <label>City: <input type="text" name="city" required></label>
                <label>ZIP Code: <input type="text" name="zip" required></label>
                <label>Country: <input type="text" value="Philippines" readonly></label>
                <label>State: <input type="text" name="state" required></label>
                <label>Phone Number: <input type="text" name="phone" required></label>
                <label><input type="checkbox" name="is_default"> Set as default address</label>

                <button type="submit" class="btn">Add Address</button>
                <button type="button" id="cancelBtn" class="btn">Cancel</button>
            </form>
        </div>

        <div class="address-list">
            <?php if (!empty($addresses)): ?>
                <?php foreach ($addresses as $address): ?>
                    <div class="address-item <?php echo $address['is_default'] ? 'default-address' : ''; ?>">
                        <p><?php echo htmlspecialchars($address['First_Name'] . ' ' . $address['Last_Name']); ?></p>
                        <p><?php echo htmlspecialchars($address['address']); ?></p>
                        <p><?php echo htmlspecialchars($address['city'] . ', ' . $address['state'] . ' ' . $address['zip']); ?></p>
                        <p><?php echo htmlspecialchars($address['phone']); ?></p>
                        <?php if ($address['is_default']): ?>
                            <span class="default-label">Default Address</span>
                        <?php else: ?>
                            <form action="set_default_address.php" method="POST" style="margin-top: 10px;">
                                <input type="hidden" name="address_id" value="<?php echo $address['id']; ?>">
                                <button type="submit" class="btn-small">Set as Default</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No addresses found. Add your first address.</p>
            <?php endif; ?>
        </div>
        
        <a href="account.php" class="btn" style="margin-top: 20px;">Back to Account</a>
    </div>

    <style>
        .address-container {
            max-width: 500px;
            margin: 95px auto;
            padding: 40px;
            background-color: rgb(216, 216, 216);
        }

        .address-container h2 {
            font-family: Arial, sans-serif;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            margin: 10px 0;
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
        
        .btn-small {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background: #000;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        .address-form {
            margin: 0px;
            padding: 20px;
        }

        .address-form label {
            display: block;
            margin: 20px 0px;
            font-weight: bold;
            text-align: left;
        }

        .address-form input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
        }

        .address-form input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }

        .hidden {
            display: none;
        }

        .address-list {
            margin-top: 30px;
        }

        .address-item {
            padding: 15px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            background: #fff;
        }

        .default-address {
            border-left: 5px solid #000;
        }

        .default-label {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background: #000;
            color: #fff;
            font-weight: bold;
        }
        
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
        }
        
        .error-message {
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ebccd1;
            border-radius: 4px;
        }
    </style>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const addAddressBtn = document.getElementById('addAddressBtn');
        const addressForm = document.getElementById('addressForm');
        const cancelBtn = document.getElementById('cancelBtn');

        addAddressBtn.addEventListener('click', () => {
            addressForm.classList.remove('hidden');
            addAddressBtn.style.display = 'none';
        });

        cancelBtn.addEventListener('click', () => {
            addressForm.classList.add('hidden');
            addAddressBtn.style.display = 'inline-block';
        });
    });
    </script>

    <?php include("footer.php"); ?>
</body>
</html>