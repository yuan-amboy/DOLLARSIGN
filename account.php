<?php
session_start();
error_log("Account page - Session ID: " . session_id());
error_log("Account page - User ID: " . ($_SESSION['users']['id'] ?? 'Not set'));
error_log("Account page - Full session: " . print_r($_SESSION, true));

if (empty($_SESSION['users']['id'])) {
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    error_log("Redirecting to login - Session empty");
    header("Location: login.php?redirect=$redirect_url");
    exit();
}

// Database connection
$host = "localhost";
$username = "root"; // Replace with your actual database username
$password = ""; // Replace with your actual database password
$database = "login_register_dollarsign";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user's default address
// Check which array key is used for email in the session
if (isset($_SESSION['users']['Email'])) {
    $user_email = $_SESSION['users']['Email'];
} elseif (isset($_SESSION['users']['email'])) {
    $user_email = $_SESSION['users']['email']; 
} else {
    // Add debugging
    error_log("Email not found in session: " . print_r($_SESSION['users'], true));
    $user_email = ""; // Fallback empty value
}

$address_query = "SELECT * FROM addresses WHERE user_email = ? AND is_default = 1 LIMIT 1";
$address_stmt = $conn->prepare($address_query);
$address_stmt->bind_param("s", $user_email);
$address_stmt->execute();
$address_result = $address_stmt->get_result();

if ($address_result->num_rows > 0) {
    $default_address = $address_result->fetch_assoc();
    $_SESSION['users']['default_address'] = $default_address['address'] . ", " . 
                                           $default_address['city'] . ", " .
                                           $default_address['state'] . " " . $default_address['zip'];
} else {
    $_SESSION['users']['default_address'] = "No default address set";
}

// Get order history
$order_query = "SELECT * FROM orders WHERE user_email = ? ORDER BY order_date DESC";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("s", $user_email);
$order_stmt->execute();
// Add debugging
error_log("Order query executed for email: $user_email");
$order_result = $order_stmt->get_result();

$orderHistory = [];
if ($order_result->num_rows > 0) {
    while ($row = $order_result->fetch_assoc()) {
        $orderHistory[] = $row;
    }
}

$address_stmt->close();
$order_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | DOLLARSIGN</title>
    <link rel="stylesheet" href="main.css">
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .account-container {
            max-width: 800px;
            margin: 120px auto 40px;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        
        .account-container h2 {
            font-size: 1.5rem;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .account-container p {
            margin-bottom: 15px;
            font-size: 1rem;
            line-height: 1;
        }
        
        .order-history-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .order-history-list li {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .order-status {
            display: inline-block;
            padding: 3px 8px;
            font-size: 0.85rem;
        }
        
        .order-status.pending { background-color: #ffeeba; color: #856404; }
        .order-status.processing { background-color: #b8daff; color: #004085; }
        .order-status.shipped { background-color: #c3e6cb; color: #155724; }
        .order-status.completed { background-color: #d4edda; color: #155724; }
        .order-status.cancelled { background-color: #f8d7da; color: #721c24; }
        
        .btn-addresses, .btn-logout {
            display: inline-block;
            padding: 8px 16px;
            background-color: #000;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-addresses {
            margin-bottom: 50px;
        }
        
        .btn-addresses:hover, .btn-logout:hover {
            background-color: #333;
        }
        
        .btn-logout {
            background-color:rgb(0, 0, 0);
            margin-top: 20px;
        }
        
        .btn-logout:hover {
            background-color:rgb(86, 86, 86);
        }
    </style>
</head>

<body>
    <?php include("navbar.php"); ?>

    <!-- Account Section -->
    <div class="account-container">
        <h2>Account Details</h2>
        <?php if (isset($_SESSION['users'])): ?>
        <p>
            <?php 
            echo htmlspecialchars($_SESSION['users']['First_Name'] ?? 'First Name') . ' ' . 
            htmlspecialchars($_SESSION['users']['Last_Name'] ?? 'Last Name');
            ?>
        </p>
        
        <p>
            <?php echo htmlspecialchars($_SESSION['users']['default_address'] ?? 'No default address set'); ?>
        </p>
        <a href="addresses.php" class="btn-addresses">Addresses</a>
        <?php else: ?>
            <p>User not logged in</p>
        <?php endif; ?>

        <h2>Order History</h2>
        <?php if (!empty($orderHistory)): ?>
            <ul class="order-history-list">
                <?php foreach ($orderHistory as $order): ?>
                    <li>
                        <strong>Order #<?php echo htmlspecialchars($order['order_id']); ?></strong> - 
                        Date: <?php echo date('M d, Y', strtotime($order['order_date'])); ?> - 
                        Status: <span class="order-status <?php echo strtolower($order['status']); ?>">
                            <?php echo htmlspecialchars(ucfirst($order['status'])); ?>
                        </span> - 
                        Total: ₱<?php echo htmlspecialchars(number_format($order['total'], 2)); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No orders found</p>
        <?php endif; ?>

        <a href="logout.php" class="btn-logout">LOGOUT</a>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>