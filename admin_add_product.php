<?php
session_start();
require_once "database.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $imageFront = $_POST['imageFront'];
    $imageBack = $_POST['imageBack'];
    $isNew = isset($_POST['isNew']) ? 1 : 0;
    
    $sql = "INSERT INTO products (name, price, description, imageFront, imageBack, isNew) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sdsssi", $name, $price, $description, $imageFront, $imageBack, $isNew);
    mysqli_stmt_execute($stmt);
    
    $_SESSION['admin_message'] = "Product added successfully";
    header("Location: admin_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | DOLLARSIGN Admin</title>
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
                <a href="admin_products.php" class="menu-item active">
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
                <h1>Add New Product</h1>
                <a href="admin_products.php" class="btn-secondary btn">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
            
            <div class="form-container">
                <form action="admin_add_product.php" method="POST">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (₱)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="imageFront">Front Image URL</label>
                        <input type="text" id="imageFront" name="imageFront" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="imageBack">Back Image URL</label>
                        <input type="text" id="imageBack" name="imageBack" required>
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="isNew" name="isNew" value="1">
                        <label for="isNew">Mark as New Arrival</label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="location.href='admin_products.php'">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>