<?php
session_start();
$productName = isset($_GET['product']) ? $_GET['product'] : '';
$isLoggedIn = isset($_SESSION["users"]) && isset($_SESSION["users"]["id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOLLARSIGN</title>
    <link rel="stylesheet" href="main.css">
    <script src="clothingData.js"></script>
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
<?php include("navbar.php"); ?>
    
    <!-- Product Details Section -->
    <div class="product-detail-container">
    <div class="product-media">
        <img id="product-image" src="" alt="Product Image" class="product-image">
    </div>

    <!-- Product Information -->
    <div class="product-info">
        <h1 id="product-name" class="product-title"></h1>
        <p id="product-price" class="product-price"></p>
        <p class="free-shipping">FREE SHIPPING</p>
        <p id="product-description" class="product-description"></p>
        
        <div class="product-options">
            <label>SIZE</label>
            <div id="size-options">
                <button class="size-button">S</button>
                <button class="size-button">M</button>
                <button class="size-button">L</button>
                <button class="size-button">XL</button>
                <button class="size-button">XXL</button>
            </div>
        </div>
        
        <div class="product-options">
            <label for="quantity">QUANTITY</label>
            <div class="quantity-container">
                <button class="quantity-button">−</button>
                <input type="number" id="quantity" class="product-quantity" min="1" value="1">
                <button class="quantity-button">+</button>
            </div>
        </div>
        
        <button class="add-to-cart">ADD TO CART</button>
    </div>
</div>

<!-- Recommendation Section -->
<?php include("recommendation.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const productName = "<?php echo htmlspecialchars($productName, ENT_QUOTES, 'UTF-8'); ?>";
    let currentProduct = null;

    // Find the current product from your clothingData.js
    if (typeof products !== 'undefined') {
        currentProduct = products.find(p => p.name === productName);

        if (currentProduct) {
            const productImage = document.getElementById('product-image');
            document.getElementById('product-name').textContent = currentProduct.name;
            document.getElementById('product-price').textContent = currentProduct.price;
            document.getElementById('product-description').textContent = currentProduct.description;

            if (currentProduct.imageFront && currentProduct.imageBack) {
                productImage.src = currentProduct.imageFront;

                productImage.addEventListener('mouseover', () => {
                    productImage.src = currentProduct.imageBack;
                });

                productImage.addEventListener('mouseout', () => {
                    productImage.src = currentProduct.imageFront;
                });
            }
        } else {
            console.error("Product not found in products array");
        }
    } else {
        console.error("Error: 'products' is not defined.");
    }

    // Size selection buttons
    const sizeButtons = document.querySelectorAll('.size-button');
    sizeButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default button behavior
            sizeButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });

    // Quantity buttons
    const quantityInput = document.getElementById('quantity');
    const quantityButtons = document.querySelectorAll('.quantity-button');
    quantityButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const currentValue = parseInt(quantityInput.value, 10);
            if (button.textContent === '−' && currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
            if (button.textContent === '+' && currentValue < 10) {
                quantityInput.value = currentValue + 1;
            }
        });
    });

    // Add to cart button
    document.querySelector('.add-to-cart').addEventListener('click', (e) => {
    e.preventDefault();

    <?php if (!$isLoggedIn): ?>
        alert("Please log in to add items to cart");
        window.location.href = 'login.php';
        return;
    <?php endif; ?>
    
    if (!currentProduct) {
        alert("Product not found.");
        return;
    }

    const selectedSize = document.querySelector('.size-button.active')?.textContent;
    if (!selectedSize) {
        alert("Please select a size before adding to cart.");
        return;
    }

    const quantity = parseInt(document.getElementById('quantity').value, 10);
    
    // Show loading state
    const addToCartBtn = document.querySelector('.add-to-cart');
    const originalText = addToCartBtn.textContent;
    addToCartBtn.textContent = 'Adding...';
    addToCartBtn.disabled = true;

    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${currentProduct.id}&size=${encodeURIComponent(selectedSize)}&quantity=${quantity}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Item added to cart!');
            if (typeof updateCartBadge === 'function') {
                updateCartBadge();
            }
        } else {
            alert('Error: ' + (data.message || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add item to cart. Please check your connection and try again.');
    })
    .finally(() => {
        addToCartBtn.textContent = originalText;
        addToCartBtn.disabled = false;
    });
});
});
</script>

<?php include("footer.php"); ?>

</body>
</html>

