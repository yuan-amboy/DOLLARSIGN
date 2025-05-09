<?php
session_start();
require_once "database.php";

// Check for order ID in URL
if (empty($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$orderId = (int)$_GET['order_id'];

// Ensure user is logged in
if (empty($_SESSION['users']['id'])) {
    header("Location: login.php?redirect=order_confirmation.php?order_id=$orderId");
    exit();
}

$userId = $_SESSION['users']['id'];

// Fetch user email from database
$userQuery = "SELECT Email FROM users WHERE ID = ?";
$userStmt = mysqli_prepare($conn, $userQuery);
mysqli_stmt_bind_param($userStmt, "i", $userId);
mysqli_stmt_execute($userStmt);
$userResult = mysqli_stmt_get_result($userStmt);
$userData = mysqli_fetch_assoc($userResult);

if (!$userData) {
    header("Location: login.php");
    exit();
}

$userEmail = $userData['Email'];

// Fetch order details
$orderQuery = "SELECT * FROM orders WHERE order_id = ? AND user_email = ?";
$orderStmt = mysqli_prepare($conn, $orderQuery);
mysqli_stmt_bind_param($orderStmt, "is", $orderId, $userEmail);
mysqli_stmt_execute($orderStmt);
$orderResult = mysqli_stmt_get_result($orderStmt);

// Check if order exists and belongs to current user
if (mysqli_num_rows($orderResult) === 0) {
    header("Location: index.php");
    exit();
}

$order = mysqli_fetch_assoc($orderResult);

// Get user's address
$addressQuery = "SELECT * FROM addresses WHERE user_email = ? AND is_default = 1";
$addressStmt = mysqli_prepare($conn, $addressQuery);
mysqli_stmt_bind_param($addressStmt, "s", $userEmail);
mysqli_stmt_execute($addressStmt);
$addressResult = mysqli_stmt_get_result($addressStmt);
$address = mysqli_fetch_assoc($addressResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | DOLLARSIGN</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
        }
        
        .confirmation-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .order-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .order-header h1 {
            margin-bottom: 5px;
            font-size: 28px;
        }
        
        .order-number {
            font-size: 16px;
            color: #777;
            margin-bottom: 5px;
        }
        
        .order-date {
            font-size: 14px;
            color: #999;
        }
        
        .confirmation-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background-color: #4CAF50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
        }
        
        .order-details {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        
        .detail-column {
            flex: 1;
            min-width: 250px;
            margin-bottom: 20px;
        }
        
        .detail-title {
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .detail-info {
            font-size: 14px;
            line-height: 1.6;
        }
        
        .detail-label {
            font-weight: 600;
            display: inline-block;
            min-width: 100px;
        }
        
        .status-pending {
            background-color: #FFC107;
            color: #000;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .price-info {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .total-row {
            font-weight: 600;
            font-size: 18px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
        }
        
        .next-steps {
            margin-top: 40px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        
        .next-steps h3 {
            margin-top: 0;
        }
        
        .next-steps ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        
        .next-steps li {
            margin-bottom: 8px;
        }
        
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        
        .continue-button {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .continue-button:hover {
            background-color: #333;
        }
        
        .email-note {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="order-header">
            <div class="confirmation-icon">✓</div>
            <h1>Thank You For Your Order!</h1>
            <div class="order-number">Order #<?= $orderId ?></div>
            <div class="order-date">
                Placed on <?= date('F j, Y', strtotime($order['order_date'])) ?> at 
                <?= date('g:i A', strtotime($order['order_date'])) ?>
            </div>
        </div>
        
        <div class="order-details">
            <div class="detail-column">
                <div class="detail-title">Order Details</div>
                <div class="detail-info">
                    <p>
                        <span class="detail-label">Status:</span> 
                        <span class="status-pending"><?= ucfirst($order['status']) ?></span>
                    </p>
                    <p>
                        <span class="detail-label">Order Total:</span> 
                        ₱<?= number_format($order['total'], 2) ?>
                    </p>
                </div>
            </div>
            
            <?php if ($address): ?>
            <div class="detail-column">
                <div class="detail-title">Shipping Address</div>
                <div class="detail-info">
                    <p><?= htmlspecialchars($address['First_Name']) ?> <?= htmlspecialchars($address['Last_Name']) ?></p>
                    <p><?= htmlspecialchars($address['address']) ?></p>
                    <p><?= htmlspecialchars($address['city']) ?>, <?= htmlspecialchars($address['state']) ?> <?= htmlspecialchars($address['zip']) ?></p>
                    <p>Philippines</p>
                    <p>Phone: <?= htmlspecialchars($address['phone']) ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="price-info">
            <div class="price-row">
                <span>Subtotal</span>
                <span>₱<?= number_format($order['total'], 2) ?></span>
            </div>
            <div class="price-row">
                <span>Shipping</span>
                <span>FREE</span>
            </div>
            <div class="price-row total-row">
                <span>Total</span>
                <span>₱<?= number_format($order['total'], 2) ?></span>
            </div>
        </div>
        
        <div class="next-steps">
            <h3>What's Next?</h3>
            <ul>
                <li>You will receive an email confirmation shortly at <?= htmlspecialchars($userEmail) ?></li>
                <li>Your order is being processed and will be shipped soon</li>
                <li>You can check your order status anytime in your account dashboard</li>
                <li>If you have any questions about your order, please contact our customer support</li>
            </ul>
        </div>
        
        <div class="button-container">
            <a href="index.php" class="continue-button">Continue Shopping</a>
        </div>
        
        <div class="email-note">
            A copy of this confirmation has been sent to your email address.
        </div>
    </div>
    
    <?php include("footer.php"); ?>
</body>
</html>