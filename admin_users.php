<?php
session_start();
require_once "database.php";

// Fetch all users
$users = [];
$sql = "SELECT * FROM users ORDER BY First_Name";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM users WHERE ID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    
    $_SESSION['admin_message'] = "User deleted successfully";
    header("Location: admin_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users | DOLLARSIGN Admin</title>
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
                <a href="admin_orders.php" class="menu-item">
                    <i class="fas fa-shopping-bag"></i> Orders
                </a>
                <a href="admin_users.php" class="menu-item active">
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
                <h1>User Management</h1>
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['ID']; ?></td>
                            <td><?php echo htmlspecialchars($user['First_Name'] . ' ' . $user['Last_Name']); ?></td>
                            <td><?php echo htmlspecialchars($user['Email']); ?></td>
                            <td>
                                <span class="status <?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <button class="action-btn view-btn" 
                                        onclick="location.href='admin_user_details.php?id=<?php echo $user['ID']; ?>'">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="action-btn delete-btn" 
                                        onclick="if(confirm('Are you sure you want to delete this user?')) { location.href='admin_users.php?delete_id=<?php echo $user['ID']; ?>'; }">
                                    <i class="fas fa-trash"></i> Delete
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