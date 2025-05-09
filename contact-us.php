<?php
session_start();
$isLoggedIn = isset($_SESSION["users"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | DOLLARSIGN</title>
    <link rel="stylesheet" href="main.css">
    <script src="clothingData.js"></script>
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php include("navbar.php"); ?>

    <!-- Contact Us -->
    <section class="contact-section">
        <div>
        <h2>CONTACT US</h2>
        </div>
    
        <div class= "contact-container">
            <!-- Google Maps Embed -->
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.019543272265!2d121.04348780000001!3d14.711486900000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b0e5bf9f66bf%3A0x7953216b71d83548!2s86%20Nitang%20Ave%2C%20Novaliches%2C%20Quezon%20City%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1741522744552!5m2!1sen!2sph" 
                width="100%" height="350" 
                style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    
        <div contact="contact-section">
            <!-- Store Location -->
            <div class="contact-info">
                <h3>Flagship Store Inquiry</h3>
                <p>Address: 86 Nitang Ave, Novaliches, Quezon City, Metro Manila</p>
            </div>
            
            <!-- Social Media Links -->
            <div class="social-media">
                <h3>Social Media Accounts</h3>
                <p>Facebook: <a href="https://web.facebook.com/profile.php?id=61550540038795" target="_blank" class="dollarsign-facebook-link">Dollar SIGN Clothing</a></p>
            </div>
        </div>
        </div>
    </section>

    <style>
  /* Contact Section */
.contact-section h2 {
    font-family: 'Arial', sans-serif;
    font-size: 3.5rem; /* Scaled for better readability */
    margin: 150px 0px 30px;
    text-align: center; /* Centering the header */
}

.contact-container {
    max-width: 800px;
    margin: auto;
    padding: 15px 20px;
    margin-top: 10px;
    background-color: rgb(216, 216, 216);
    margin-bottom: 50px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Hover effect for contact container */
.contact-container:hover {
    transform: translateY(-5px); /* Slight lift effect on hover */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); /* More prominent shadow */
}

.contact-section h3 {
    font-family: Arial, sans-serif;
    font-size: 1rem; /* Slightly smaller for better balance */
    margin: 50px 0px 0px;
    color: #333; /* Darker text for better readability */
}

.contact-section p {
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 20px;
    color: #555; /* Slightly lighter text for a softer appearance */
}

.contact-section a {
    color: rgb(0, 0, 0);
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s ease;
}

.contact-section a:hover {
    text-decoration: underline;
    color: #007bff; /* Add color on hover for emphasis */
}

/* Responsive Styles */
@media (max-width: 768px) {
    .contact-section h2 {
        font-size: 2.2rem; /* Scale down the header size */
        margin-top: 100px; /* Reduce top margin */
    }

    .contact-container {
        padding: 15px;
        margin-bottom: 30px; /* Decrease margin for smaller screens */
    }

    .contact-section h3 {
        font-size: 1rem; /* Keep h3 size consistent */
    }

    .contact-section p {
        font-size: 0.9rem; /* Slightly smaller font for better mobile readability */
    }
}
            </style>

    <?php include("footer.php"); ?>
</body>
</html>
