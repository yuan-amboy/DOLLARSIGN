<?php
session_start();
$isLoggedIn = isset($_SESSION["users"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | DOLLARSIGN</title>
    <link rel="stylesheet" href="main.css">
    <script src="clothingData.js"></script>
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php include("navbar.php"); ?>

    <!-- About Us Content -->
    <section class="about-us-content">
        <div class="about-us-title">
        <h1>ABOUT US</h1>
        </div>
        <div class="beat-street">
        <p style="text-align: justify;">In today's digital era, having an online store is essential for any clothing brand looking to grow. <b>DOLLARSIGN</b> is more than just a streetwear brand—it’s a movement inspired by urban culture, bold designs, and the relentless hustle mindset. Our signature graffiti-style aesthetics reflect the raw energy of the streets, embracing individuality and confidence.</p>
        <p style="text-align: justify;">To connect with more people and strengthen our presence, we’ve created an official e-commerce platform where customers can easily browse and purchase our latest collections. Designed for simplicity and style, our website ensures a seamless shopping experience with secure payment options and effortless navigation.</p>
        <p style="text-align: justify;">At <b>DOLLARSIGN</b>, we’re not just selling clothes—we’re representing a lifestyle. This platform is built for the hustlers, the trendsetters, and those who wear their ambition as boldly as their style. Wherever you are, the hustle never stops.</p>
        <p style="text-align: justify;"><b>Welcome to DOLLARSIGN. Wear the grind. Own the streets.</b></p>
        </div>
    </section>

<?php include("footer.php"); ?>

</body>
</html>
