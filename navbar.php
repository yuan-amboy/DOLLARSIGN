<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']); // Get current script name
$isLoggedIn = !empty($_SESSION['users']['id']);
?>

<!-- Navigation Bar -->
<header>
    <div class="nav-wrapper">
        <nav class="navbar">
            <div class="nav-container">
                <button id="menu-toggle" class="menu-button">
                    <img src="images/menu-icon.png" alt="Menu" class="menu-icon">
                    <img src="images/close-icon.png" alt="Close" class="close-icon">
                </button>   

                <a href="index.php">
                    <img src="images/logo.png" alt="DollarSign Logo" class="logo">
                </a>

                <div class="nav-icons">
                    <?php if ($isLoggedIn): ?>
                        <a href="account.php" id="user-account-link">
                            <img src="images/user-icon.png" alt="Account" class="user-icon">
                        </a>
                    <?php else: ?>
                        <?php 
                        $redirect = ($current_page !== "login.php" && $current_page !== "account.php") 
                                    ? "?redirect=" . urlencode($_SERVER['REQUEST_URI']) 
                                    : "";
                        ?>
                        <a href="login.php<?php echo $redirect; ?>">
                            <img src="images/user-icon.png" alt="Login" class="user-icon">
                        </a>
                    <?php endif; ?>

                    <div class="cart-container">
                        <img src="images/cart-icon.png" alt="Cart" class="cart-icon">
                        <span id="cart-count" class="cart-badge">0</span>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Menu -->
         <div class="menu-section">
            <div class="menu-search">
                <input type="text" id="search-input" placeholder="Search...">
            </div>
            <ul>
                <li><a href="index.php">HOME</a></li>
                <li><a href="shop.php">SHOP</a></li>
                <li><a href="about-us.php">ABOUT US</a></li>
                <li><a href="how-to-order.php">HOW TO ORDER</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Sliding Cart Panel -->
    <div id="cart-panel" class="cart-panel">
        <div class="cart-header">
            <h2>Your Cart</h2>
            <button id="close-cart" class="close-cart">&times;</button>
        </div>

        <div id="cart-items" class="cart-items"></div>

        <div id="cart-summary" class="cart-summary" style="display: none;">
            <p>Total: ₱<span id="cart-total">0.00</span></p>
            <button id="checkout-btn">PROCEED TO CHECKOUT</button>
        </div>

        <div id="continue-shopping" class="continue-shopping" style="display: none;">
            <a href="javascript:void(0)" onclick="closeCart()">Continue shopping</a>
        </div>
    </div>

    <div id="overlay" class="overlay"></div>

    <script>
        document.addEventListener('DOMContentLoaded', updateCartBadge);

        const cartPanel = document.getElementById('cart-panel');
        const closeCartButton = document.getElementById('close-cart');
        const overlay = document.getElementById('overlay');
        const cartContainer = document.getElementById('cart-items');

        // Open cart
        document.querySelector('.cart-icon').addEventListener('click', () => {
            loadCart();
            cartPanel.classList.add('open');
            overlay.style.display = 'block';
        });

        // Close cart
        function closeCart() {
            cartPanel.classList.remove('open');
            overlay.style.display = 'none';
        }

        closeCartButton.addEventListener('click', closeCart);
        overlay.addEventListener('click', closeCart);

        function updateCartBadge() {
    fetch('get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const cartCount = document.getElementById('cart-count');
            cartCount.textContent = data.count;
            cartCount.style.display = data.count > 0 ? 'flex' : 'none';
        });
}

function loadCart() {
    fetch('get_cart_items.php')
        .then(response => response.json())
        .then(data => {
            const cartContainer = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');
            const cartSummary = document.getElementById('cart-summary');
            const continueShopping = document.getElementById('continue-shopping');

            cartContainer.innerHTML = '';

            if (data.items.length === 0) {
                continueShopping.style.display = 'block';
                cartSummary.style.display = 'none';
                return;
            }

            let total = 0;
            data.items.forEach((item, index) => {
                const itemElement = document.createElement('div');
                itemElement.className = 'cart-item';
                itemElement.innerHTML = `
                    <img src="${item.imageFront}" alt="${item.name}" class="cart-item-img">
                    <div>
                        <p><b>${item.name} (${item.size})</b></p>
                        <p>₱${item.price.toFixed(2)}</p>
                        <div class="quantity-container">
                            <button class="quantity-btn" onclick="adjustQuantity(${item.id}, -1)">
                                ${item.quantity === 1 ? '🗑️' : '−'}
                            </button>
                            <span>${item.quantity}</span>
                            <button class="quantity-btn" onclick="adjustQuantity(${item.id}, 1)">+</button>
                        </div>
                    </div>
                `;
                cartContainer.appendChild(itemElement);

                total += item.price * item.quantity;
            });

            cartTotal.textContent = total.toFixed(2);
            cartSummary.style.display = 'block';
        });
}

function adjustQuantity(cartId, change) {
    fetch('update_cart_quantity.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cart_id=${cartId}&change=${change}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCart();
            updateCartBadge();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

        // Handle Proceed to Checkout
        document.getElementById('checkout-btn').addEventListener('click', () => {
    fetch('get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            if (data.count === 0) {
                alert('Your cart is empty!');
                return;
            }
            window.location.href = 'checkout.php';
        });
});
</script>
</header>