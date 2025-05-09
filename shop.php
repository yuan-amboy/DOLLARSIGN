<?php
session_start();
$isLoggedIn = isset($_SESSION["users"]);
require_once "database.php";

$products = [];
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

// Check for DB errors
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | DOLLARSIGN</title>
    <link rel="stylesheet" href="main.css">
    <script>
        const shopProducts = <?php echo json_encode($products); ?>;
    </script>
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
<?php include("navbar.php"); ?>
    
    <!-- Shop Search Bar -->
    <div class="shop-header">
        <input type="text" id="shop-search-input" placeholder="Search products...">
    </div>

    <div id="no-results-message" style="display: none;">No products found.</div>

    <div id="shop-items" class="shop-section">
        <!-- Products will be injected here by JavaScript -->
    </div>

    <?php include("footer.php"); ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            
            // Get the search input element and product display section
            const shopSearchInput = document.getElementById("shop-search-input");
            const shopItems = document.getElementById("shop-items");

            // Load products (this should be handled server-side via PHP and database, already done in your shop.php)
            const products = <?php echo json_encode($products); ?>;

            // Function to display products on the page
            function displayProducts(productList) {
                if (!shopItems) return;

                // Clear current products before displaying the filtered ones
                shopItems.innerHTML = '';

                // Display the filtered products
                shopItems.innerHTML = productList.map(product => `
                    <a href="product.php?product=${encodeURIComponent(product.name)}" class="product-link">
                        <div class="product-container">
                            <div class="product-card"
                                onmouseover="this.querySelector('.product-image').src='${product.imageBack || product.imageFront}'"
                                onmouseout="this.querySelector('.product-image').src='${product.imageFront}'">
                                <img src="${product.imageFront}" alt="${product.name}" class="product-image" style="transition: 0.3s ease;">
                                <p class="name">${product.name}</p>
                                <p class="price">${product.price}</p>
                            </div>
                        </div>
                    </a>
                `).join("");
            }

            // Function to filter products based on the search term
            function filterShopItems(query) {
                const lowerCaseQuery = query.toLowerCase();

                // Filter products by name (or any other criteria like description or category)
                const filteredProducts = products.filter(product => 
                    product.name.toLowerCase().includes(lowerCaseQuery)
                );

                // If no products match the search term, display a 'No products found' message
                if (filteredProducts.length === 0) {
                    shopItems.innerHTML = '<p>No products found.</p>';
                } else {
                    displayProducts(filteredProducts);
                }
            }

            // Event listener for the search input
            shopSearchInput.addEventListener("input", function () {
                filterShopItems(shopSearchInput.value);
            });

            // Initially display all products (in case the user hasn't searched yet)
            displayProducts(products);
            });
    </script>
</body>
</html>
