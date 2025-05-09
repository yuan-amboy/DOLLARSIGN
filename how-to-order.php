<?php
session_start();
$isLoggedIn = isset($_SESSION["users"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to Order | DOLLARSIGN</title>
    <link rel="stylesheet" href="main.css">
    <script src="clothingData.js"></script>
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php include("navbar.php"); ?>

<!-- How to Order -->
<div class="how-to-order-title">
    <h1>HOW TO ORDER</h1>
</div>

<div class="how-to-order-content">
    <h3>INSTRUCTIONS:</h3>
    <p>- Go to <b>SHOP</b> section to browse all the products available at our online shop.</p>
    <p>- <b>ADD TO CART</b> the product/s that you wish to buy.</p>
    <p>- If you are satisfied with your order/s and wish to check out, just click the shopping bag icon and proceed to <b>CHECKOUT</b>.</p>
    <p>- Input your email address and the necessary information needed on the <b>SHIPPING ADDRESS</b> section.</p>
    <p>- Proceed to <b>CONTINUE TO SHIPPING</b>.</p>
    <p>- For the payment, you can pay using your GCASH.</p>
    <p>- Click <b>COMPLETE ORDER</b> once you choose your mode of payment.</p>
    <p>- Please take note of your order number.</p>
    <p>- You will receive updates regarding your order/s through email.</p>
    <p>If you have more questions and concerns, please don't hesitate to contact our DOLLARSIGN customer service. You may reach our customer service by sending us an email at dollarsign.clothing@gmail.com</a>.</p>
</div>

<style>
/* Desktop Styles */
.how-to-order-title {
  font-family: 'Arial', sans-serif;
  text-align: center;
  transition: all 0.4s ease-out;
}

.how-to-order-title h1 {
  margin: 6.5rem 0 1.5rem;
  font-size: 2.8rem;
  font-weight: 700;
  color: #222;
  letter-spacing: -0.5px;
  line-height: 1.2;
  transition: all 0.3s ease;
}

.how-to-order-title h3 {
  font-family: Arial, sans-serif;
  font-size: 0.875rem;
  color: #ffffff;
  font-weight: 400;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  margin-bottom: 2rem;
  transition: all 0.3s ease;
}

.how-to-order-content {
  font-family: Arial, sans-serif;
  font-size: 1rem;
  line-height: 1.8;
  letter-spacing: 0.5px;
  color: #ffffff;
  background-color: #111111;
  padding: 2.5rem;
  max-width: 700px;
  margin: 0 auto 3rem;
  text-align: left;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
}

.how-to-order-content a {
  color: #ffffff;
  text-decoration: underline;
  font-weight: 600;
  transition: all 0.2s ease;
}

.how-to-order-content a:hover {
  color: #444;
  text-decoration: none;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .how-to-order-title h1 {
    margin: 4rem 0 1rem;
    font-size: 2rem;
    padding: 0 1rem;
  }
  
  .how-to-order-title h3 {
    font-size: 0.75rem;
    margin-bottom: 1.5rem;
    padding: 0 1rem;
  }
  
  .how-to-order-content {
    padding: 1.5rem;
    margin: 0 1rem 2rem;
    font-size: 0.9rem;
    line-height: 1.7;
  }
}
</style>

<?php include("footer.php"); ?>

</body>
</html>
