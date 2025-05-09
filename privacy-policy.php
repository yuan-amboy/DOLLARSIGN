<?php
session_start();
$isLoggedIn = isset($_SESSION["users"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | DOLLARSIGN</title>
    <link rel="stylesheet" href="main.css">
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php include("navbar.php"); ?>

    <!-- Privacy Policy Section -->
    <h2 class="privacy-title">PRIVACY POLICY</h2>
    <div class="privacy-section">

        <div class="privacy-item">
            <h3>Introduction</h3>
            <p>Dollar Sign Clothing is committed to protecting your privacy. This Privacy Policy explains how we collect, use, and protect your personal information when you use our website and services.</p>
        </div>

        <div class="privacy-item">
            <h3>Information We Collect</h3>
            <p>We may collect the following types of information:</p>
            <ul>
                <li><strong>Personal Information:</strong> Name, email address, shipping address, payment information.</li>
                <li><strong>Usage Information:</strong> IP address, browser type, pages visited, and time spent on the website.</li>
            </ul>
        </div>

        <div class="privacy-item">
            <h3>How We Use Your Information</h3>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Process and fulfill orders</li>
                <li>Improve our website and services</li>
                <li>Send promotional emails and newsletters (with your consent)</li>
                <li>Provide customer support</li>
            </ul>
        </div>

        <div class="privacy-item">
            <h3>Cookies</h3>
            <p>Our website uses cookies to enhance your experience. Cookies are small files that store information about your preferences. You can disable cookies through your browser settings, though this may affect your experience on our website.</p>
        </div>

        <div class="privacy-item">
            <h3>Sharing Your Information</h3>
            <p>We do not sell, rent, or trade your personal information to third parties. We may share your information with trusted service providers, such as payment processors and shipping companies, to complete transactions.</p>
        </div>

        <div class="privacy-item">
            <h3>Data Security</h3>
            <p>We implement appropriate security measures to protect your personal data. However, please be aware that no method of data transmission over the internet is 100% secure.</p>
        </div>

        <div class="privacy-item">
            <h3>Your Rights</h3>
            <p>You have the right to access, correct, or delete your personal information at any time. To make changes, please contact our customer service team.</p>
        </div>

        <div class="privacy-item">
            <h3>Changes to the Privacy Policy</h3>
            <p>We reserve the right to update or change this Privacy Policy. Any changes will be posted on this page, and the revised policy will be effective immediately upon posting.</p>
        </div>

        <div class="privacy-item">
            <h3>Contact Us</h3>
            <p>If you have any questions about our Privacy Policy, please contact us at:</p>
            <ul>
                <li><strong>Email:</strong> dollarsign.clothing@gmail.com</li>
                <li><strong>Facebook:</strong> <a href="https://web.facebook.com/profile.php?id=61550540038795" target="_blank" class="dollarsign-facebook-link">Dollar Sign Clothing</a></li>
            </ul>
        </div>

    </div>

    <style>
 /* Privacy Title */
.privacy-title {
    font-family: 'Arial', sans-serif;
    font-size: 3.5rem; /* Scaled down for mobile friendliness */
    text-align: center;
    margin: 150px 0px 10px;
}

/* Privacy Section Container */
.privacy-section {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    box-sizing: border-box;
    background-color: rgb(216, 216, 216);
    margin-bottom: 50px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Hover effect for privacy section */
.privacy-section:hover {
    transform: translateY(-5px); /* Slight lift on hover */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); /* More prominent shadow */
}

/* Privacy Item Styling */
.privacy-item {
    margin-bottom: 20px;
    line-height: 1.5;
    text-align: justify;
}

/* Privacy Item Heading */
.privacy-item h3 {
    font-size: 1.6rem; /* Adjusted size for better mobile readability */
    margin: 0px 0px 10px;
    font-weight: bold;
    color: #333; /* Darker color for better readability */
}

/* Privacy Item Paragraphs & Lists */
.privacy-item p, .privacy-item ul {
    font-size: 1rem; /* Adjusted for better consistency */
    color: #555; /* Slightly lighter color for readability */
}

/* Privacy Item List */
.privacy-item ul {
    padding-left: 20px;
}

/* Privacy List Items */
.privacy-item li {
    margin: 5px 0;
}

/* Facebook Link Styling */
.dollarsign-facebook-link {
    color: rgb(0, 0, 0);
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

/* Hover effect for Facebook link */
.dollarsign-facebook-link:hover {
    text-decoration: underline;
    color: #007bff; /* Change color to blue on hover */
}

/* Responsive Styles */
@media (max-width: 768px) {
    .privacy-title {
        font-size: 2.2rem; /* Scale down title size */
        margin-top: 100px; /* Adjust top margin for mobile */
    }

    .privacy-section {
        padding: 15px;
        margin-bottom: 30px; /* Slightly reduce bottom margin */
    }

    .privacy-item h3 {
        font-size: 1.3rem; /* Slightly smaller headings for mobile */
    }

    .privacy-item p, .privacy-item ul {
        font-size: 0.95rem; /* Smaller text for mobile */
    }
}
    </style>

    <?php include("footer.php"); ?>
</body>
</html>
