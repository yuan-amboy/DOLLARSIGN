<?php
session_start();
require_once "database.php";

// Redirect if not logged in
if (empty($_SESSION['users']['id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit();
}

$userId = $_SESSION['users']['id'];

// Fetch the user's email directly from the database using the user ID
$userQuery = "SELECT Email FROM users WHERE ID = ?";
$userStmt = mysqli_prepare($conn, $userQuery);
mysqli_stmt_bind_param($userStmt, "i", $userId);
mysqli_stmt_execute($userStmt);
$userResult = mysqli_stmt_get_result($userStmt);
$userData = mysqli_fetch_assoc($userResult);

// Make sure we have a valid email from the database
if (!$userData || empty($userData['Email'])) {
    // Handle the error - redirect to login or display an error message
    header("Location: login.php?error=invalid_user");
    exit();
}

$userEmail = $userData['Email'];

// Fetch user's default address
$defaultAddress = [];
$addressSql = "SELECT * FROM addresses WHERE user_email = ? AND is_default = 1";
$addressStmt = mysqli_prepare($conn, $addressSql);
mysqli_stmt_bind_param($addressStmt, "s", $userEmail);
mysqli_stmt_execute($addressStmt);
$addressResult = mysqli_stmt_get_result($addressStmt);
if ($addressResult && mysqli_num_rows($addressResult) > 0) {
    $defaultAddress = mysqli_fetch_assoc($addressResult);
}

// Fetch cart items
$cartItems = [];
$cartTotal = 0;
$cartSql = "SELECT c.*, p.name, p.price, p.imageFront 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?";
$cartStmt = mysqli_prepare($conn, $cartSql);
mysqli_stmt_bind_param($cartStmt, "i", $userId);
mysqli_stmt_execute($cartStmt);
$cartResult = mysqli_stmt_get_result($cartStmt);

while ($item = mysqli_fetch_assoc($cartResult)) {
    $cartItems[] = $item;
    $cartTotal += $item['price'] * $item['quantity'];
}

// Process checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name'] ?? '');
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $city = mysqli_real_escape_string($conn, $_POST['city'] ?? '');
    $state = mysqli_real_escape_string($conn, $_POST['state'] ?? '');
    $zip = mysqli_real_escape_string($conn, $_POST['zip'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $paymentMethod = mysqli_real_escape_string($conn, $_POST['payment'] ?? '');
    $saveAddress = isset($_POST['save_address']) ? 1 : 0;
    
    // Create order in database
    $orderSql = "INSERT INTO orders (user_email, status, total) VALUES (?, 'pending', ?)";
    $orderStmt = mysqli_prepare($conn, $orderSql);
    mysqli_stmt_bind_param($orderStmt, "sd", $userEmail, $cartTotal);
    
    // Try to execute and handle potential errors
    if (!mysqli_stmt_execute($orderStmt)) {
        // Log the error for debugging
        error_log("Order creation failed: " . mysqli_stmt_error($orderStmt));
        
        // Redirect with error message
        header("Location: checkout.php?error=order_failed");
        exit();
    }
    
    $orderId = mysqli_insert_id($conn);
    
    if ($orderId) {
        // Save address if requested
        if ($saveAddress) {
            $addressSql = "INSERT INTO addresses (user_email, First_Name, Last_Name, address, city, state, zip, phone, is_default)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
                           ON DUPLICATE KEY UPDATE is_default = 1";
            $addressStmt = mysqli_prepare($conn, $addressSql);
            mysqli_stmt_bind_param($addressStmt, "ssssssss", $userEmail, $firstName, $lastName, $address, $city, $state, $zip, $phone);
            mysqli_stmt_execute($addressStmt);
        }
        
        // Clear cart
        $clearCartSql = "DELETE FROM cart WHERE user_id = ?";
        $clearCartStmt = mysqli_prepare($conn, $clearCartSql);
        mysqli_stmt_bind_param($clearCartStmt, "i", $userId);
        mysqli_stmt_execute($clearCartStmt);
        
        // Redirect to thank you page
        header("Location: order_confirmation.php?order_id=$orderId");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | DOLLARSIGN</title>
    <link rel="stylesheet" href="checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Navigation Bar */
.navbar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 78px;
  background-color: #111111;
  padding: 0 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  transition: height 0.3s ease, padding 0.3s ease;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.nav-container {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  max-width: 1200px;
  transition: all 0.3s ease;
}

.logo {
  height: 45px;
  cursor: pointer;
  display: block;
  filter: invert(1);
  transition: height 0.3s ease, transform 0.3s ease;
  margin: 0 auto;
}

.logo:hover {
  transform: scale(1.05);
}
    </style>
</head>
<body>
<header>
    <div class="nav-wrapper">
        <nav class="navbar">
            <div class="nav-container">
                <a href="index.php">
                    <img src="images/logo.png" alt="DollarSign Logo" class="logo">
                </a>
            </div>
        </nav>

    <?php if (isset($_GET['error']) && $_GET['error'] == 'order_failed'): ?>
    <div class="error-message" style="background-color: #ffeeee; color: #dd0000; padding: 10px; margin-bottom: 15px; text-align: center;">
        There was an error processing your order. Please try again or contact support.
    </div>
    <?php endif; ?>
    
    <form method="POST" class="checkout-container">
        <div class="checkout-form">
            <div class="form-section">
                <h2>Contact</h2>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($userEmail) ?>" readonly>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="email-offers" name="email-offers">
                    <label for="email-offers">Email me with news and offers</label>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Delivery</h2>
                <div class="form-group">
                    <label for="country">Country/Region</label>
                    <select id="country" name="country" required>
                        <option value="Philippines" selected>Philippines</option>
                        <option value="Iran">Iran</option>
                    </select>
                </div>
                
                <div class="form-group" style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label for="first-name">First name</label>
                        <input type="text" id="first-name" name="first_name" 
                               value="<?= htmlspecialchars($defaultAddress['First_Name'] ?? '') ?>" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="last-name">Last name</label>
                        <input type="text" id="last-name" name="last_name" 
                               value="<?= htmlspecialchars($defaultAddress['Last_Name'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" 
                           value="<?= htmlspecialchars($defaultAddress['address'] ?? '') ?>" 
                           placeholder="Street address" required>
                </div>
                
                <div class="form-group">
                    <label for="address2">Apartment, suite, etc. (optional)</label>
                    <input type="text" id="address2" name="address2">
                </div>
                
                <div class="form-group" style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" 
                               value="<?= htmlspecialchars($defaultAddress['city'] ?? '') ?>" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" 
                               value="<?= htmlspecialchars($defaultAddress['state'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="form-group" style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label for="zip">ZIP code</label>
                        <input type="text" id="zip" name="zip" 
                               value="<?= htmlspecialchars($defaultAddress['zip'] ?? '') ?>" required>
                    </div>
                    <div style="flex: 1;">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?= htmlspecialchars($defaultAddress['phone'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="save-address" name="save_address" checked>
                    <label for="save-address">Save this information for next time</label>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Shipping Method</h2>
                <p>Free Shipping Nationwide</p>
                <p>Enter your shipping address to view available shipping methods.</p>
            </div>
            
            <div class="form-section">
                <h2>Payment</h2>
                <p>All transactions are secure and encrypted</p>
                
                <div class="payment-methods">
                    <div class="payment-method">
                        <input type="radio" id="gcash" name="payment" value="GCash" checked required>
                        <label for="gcash">GCash</label>
                    </div>
                    
                    <div class="payment-method">
                        <input type="radio" id="paymaya" name="payment" value="PayMaya" required>
                        <label for="paymaya">PayMaya</label>
                    </div>
                    
                    <div class="payment-method">
                        <input type="radio" id="credit-card" name="payment" value="Credit Card" required>
                        <label for="credit-card">Credit/Debit Card</label>
                    </div>
                </div>
                
                <div class="secure-payment">
                    <span>After clicking "Pay now", you will be redirected to complete your purchase securely</span>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Billing address</h2>
                <div class="checkbox-group">
                    <input type="checkbox" id="same-as-shipping" name="same_as_shipping" checked>
                    <label for="same-as-shipping">Same as shipping address</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="different-billing" name="different_billing">
                    <label for="different-billing">Use a different billing address</label>
                </div>
            </div>
            
            <button type="submit" class="btn-pay">PAY NOW</button>
        </div>
        
        <div class="order-summary">
            <h2>Order Summary</h2>
            
            <?php foreach ($cartItems as $item): ?>
            <div class="order-item">
                <img src="<?= htmlspecialchars($item['imageFront']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <div class="order-item-details">
                    <div class="order-item-name">
                        <?= htmlspecialchars($item['name']) ?> (<?= htmlspecialchars($item['size']) ?>)
                    </div>
                    <div class="order-item-price">₱<?= number_format($item['price'], 2) ?></div>
                    <div>Quantity: <?= htmlspecialchars($item['quantity']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="order-total">
                <div style="display: flex; justify-content: space-between;">
                    <span>Subtotal</span>
                    <span>₱<?= number_format($cartTotal, 2) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Shipping</span>
                    <span>FREE</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 15px;">
                    <span>Total</span>
                    <span>₱<?= number_format($cartTotal, 2) ?></span>
                </div>
            </div>
        </div>
    </form>
    
    <?php include("footer.php"); ?>
    
    <script>
        // Handle billing address checkboxes
        document.getElementById('same-as-shipping').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('different-billing').checked = false;
            }
        });
        
        document.getElementById('different-billing').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('same-as-shipping').checked = false;
            }
        });
        
        // Handle payment method selection
        const paymentMethods = document.querySelectorAll('.payment-method');
        paymentMethods.forEach(method => {
            method.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update UI for selected method
                paymentMethods.forEach(m => m.style.borderColor = '#ddd');
                this.style.borderColor = '#000';
            });
        });
    </script>
</body>
</html>