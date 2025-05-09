<?php
session_start();

if (!isset($_SESSION["users"])) {
    header("Location: login.php");
    exit();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

require_once "database.php";

// Fetch statistics
$stats = [];
$sql = "SELECT COUNT(*) as total_users FROM users";
$result = mysqli_query($conn, $sql);
$stats['total_users'] = mysqli_fetch_assoc($result)['total_users'];

$sql = "SELECT COUNT(*) as total_orders FROM orders";
$result = mysqli_query($conn, $sql);
$stats['total_orders'] = mysqli_fetch_assoc($result)['total_orders'];

$sql = "SELECT SUM(total) as total_revenue FROM orders WHERE status = 'completed'";
$result = mysqli_query($conn, $sql);
$stats['total_revenue'] = mysqli_fetch_assoc($result)['total_revenue'] ?? 0;

// Fetch recent orders
$recentOrders = [];
$sql = "SELECT o.order_id, o.user_email, o.order_date, o.total, o.status, 
               u.First_Name, u.Last_Name 
        FROM orders o
        JOIN users u ON o.user_email = u.Email
        ORDER BY o.order_date DESC LIMIT 5";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $recentOrders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | DOLLARSIGN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <script>
    // Force reload when navigating with back button after logout
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
    </script>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>DOLLARSIGN ADMIN</h2>
            </div>
            <div class="sidebar-menu">
                <a href="admin.php" class="menu-item active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="admin_products.php" class="menu-item">
                    <i class="fas fa-tshirt"></i> Products
                </a>
                <a href="admin_orders.php" class="menu-item">
                    <i class="fas fa-shopping-bag"></i> Orders
                </a>
                <a href="admin_users.php" class="menu-item">
                    <i class="fas fa-users"></i> Users
                </a>
                <a href="logout.php" class="menu-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card users">
                    <h3>Total Users</h3>
                    <p><?php echo $stats['total_users']; ?></p>
                </div>
                <div class="stat-card orders">
                    <h3>Total Orders</h3>
                    <p><?php echo $stats['total_orders']; ?></p>
                </div>
                <div class="stat-card revenue">
                    <h3>Total Revenue</h3>
                    <p>₱<?php echo number_format($stats['total_revenue'], 2); ?></p>
                </div>
                <div class="stat-card products">
                    <h3>Total Products</h3>
                    <?php
                    $sql = "SELECT COUNT(*) as total_products FROM products";
                    $result = mysqli_query($conn, $sql);
                    $stats['total_products'] = mysqli_fetch_assoc($result)['total_products'];
                    ?>
                    <p><?php echo $stats['total_products']; ?></p>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="table-container">
                <div class="table-header">
                    <h2>Recent Orders</h2>
                    <a href="admin_orders.php" class="view-all">View All</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['First_Name'] . ' ' . $order['Last_Name']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                            <td>₱<?php echo number_format($order['total'], 2); ?></td>
                            <td>
                                <span class="status <?php echo strtolower($order['status']); ?>">
                                    <?php echo $order['status']; ?>
                                </span>
                            </td>
                            <td>
                                <button class="action-btn view-btn" 
                                        onclick="location.href='admin_order_details.php?id=<?php echo $order['order_id']; ?>'">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>