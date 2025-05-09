<?php
session_start();
require_once "database.php";

// Fetch all orders
$orders = [];
$sql = "SELECT o.order_id, o.user_email, o.order_date, o.total, o.status, 
               u.First_Name, u.Last_Name 
        FROM orders o
        JOIN users u ON o.user_email = u.Email
        ORDER BY o.order_date DESC";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

// Handle status update
if (isset($_GET['update_status'])) {
    $order_id = $_GET['order_id'];
    $new_status = $_GET['new_status'];
    
    $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $order_id);
    mysqli_stmt_execute($stmt);
    
    $_SESSION['admin_message'] = "Order status updated successfully";
    header("Location: admin_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | DOLLARSIGN Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar (same as admin.php) -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>DOLLARSIGN ADMIN</h2>
            </div>
            <div class="sidebar-menu">
                <a href="admin.php" class="menu-item">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="admin_products.php" class="menu-item">
                    <i class="fas fa-tshirt"></i> Products
                </a>
                <a href="admin_orders.php" class="menu-item active">
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
                <h1>Order Management</h1>
            </div>
            
            <?php if (isset($_SESSION['admin_message'])): ?>
                <div class="alert alert-success" style="padding: 10px; background: #d4edda; color: #155724; margin-bottom: 20px; border-radius: 4px;">
                    <?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message']); ?>
                </div>
            <?php endif; ?>
            
            <div class="table-container">
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
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['First_Name'] . ' ' . $order['Last_Name']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                            <td>₱<?php echo number_format($order['total'], 2); ?></td>
                            <td>
                                <form action="admin_orders.php" method="GET" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <select name="new_status" onchange="this.form.submit()">
                                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td>
                                <button class="action-btn view-btn" 
                                        onclick="location.href='admin_order_details.php?id=<?php echo $order['order_id']; ?>'">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn edit-btn" 
                                        onclick="location.href='admin_edit_order.php?id=<?php echo $order['order_id']; ?>'">
                                    <i class="fas fa-edit"></i> Edit
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