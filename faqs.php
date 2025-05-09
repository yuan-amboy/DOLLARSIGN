<?php
session_start();
$isLoggedIn = isset($_SESSION["users"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs | DOLLARSIGN</title>
    <link rel="stylesheet" href="main.css">
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php include("navbar.php"); ?>

<!-- FAQ Section -->
<div class="faq-section">
    <h2>FAQs</h2>

    <div class="faq-item">
        <div class="faq-question">What are your shipping costs and delivery times?</div>
        <div class="faq-answer">You can see the exact shipping cost at checkout and typically takes 2 to 3 days within Metro Manila and 3-5 days if you are in province.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Do you offer local delivery?</div>
        <div class="faq-answer">Yes, we offer local delivery within [City/Region]. For deliveries within our local area, you can expect your order to arrive in 2-5 days depending on your location.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">What happens if my order is damaged or lost in transit?</div>
        <div class="faq-answer">If your order arrives damaged, please contact us within [Number] days with photos of the damage and your order number. If your order is lost, we will investigate with the shipping carrier and either issue a refund or reship your order.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">What is your return policy?</div>
        <div class="faq-answer">We offer [Number]-day returns on unworn, unwashed items with original tags attached. See our full return policy [link to return policy page].</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">What materials are your clothes made of?</div>
        <div class="faq-answer">We use a variety of high-quality materials, including [List materials, e.g., Cotton, Polyester Blend, Silkscreen]. Specific material details are listed on each product page.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">How do I find the right size?</div>
        <div class="faq-answer">Please refer to our detailed size chart [link to size chart] for accurate measurements. If you're still unsure, please contact us.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Do you offer plus sizes?</div>
        <div class="faq-answer">Yes, we offer sizes [Size range].</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">What payment methods do you accept?</div>
        <div class="faq-answer">We accept Cash and GCash only.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">How can I contact customer service?</div>
        <div class="faq-answer">You can contact us via Page of our Clothing Brand.</div>
    </div>

    <div class="faq-item">
        <div class="faq-question">Are your clothes ethically made?</div>
        <div class="faq-answer">Yes, we are committed to ethical and sustainable practices. [Elaborate on your brand's ethical and sustainable practices].</div>
    </div>
</div>

<style>
/* Base FAQ Section Styling */
.faq-section {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
}

.faq-section h2 {
    font-family: 'Arial', sans-serif;
    font-size: 3.5rem;
    margin: 60px 0px 25px;
    text-align: center;
}

/* FAQ Items */
.faq-item {
    border-bottom: 1px solid #ccc;
    overflow: hidden;
    transition: max-height 0.4s ease, opacity 0.3s ease;
}

/* FAQ Question */
.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    font-weight: bold;
    cursor: pointer;
    background-color: rgb(216, 216, 216);
    margin: 10px 0;
    transition: background 0.3s ease;
}

.faq-question:hover {
    background: #e8e8e8;
}

.faq-question::after {
    content: '+';
    font-size: 24px;
    transition: transform 0.3s ease;
}

/* FAQ Answer */
.faq-answer {
    padding: 10px 20px;
    background-color: rgb(245, 245, 245);
    display: none;
    opacity: 0;
    text-align: justify;
    line-height: 1.5;
    animation: fadeIn 0.3s ease-in-out forwards;
}

.faq-item.open .faq-answer {
    display: block;
    opacity: 1;
}

.faq-item.open .faq-question::after {
    content: '-';
    transform: rotate(180deg);
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Responsive Styles */
@media (max-width: 768px) {
    .faq-section {
        padding: 15px;
        margin: 30px 15px;
    }

    .faq-section h2 {
        font-size: 2.2rem;
    }

    .faq-question {
        flex-direction: row;
        padding: 12px 16px;
    }

    .faq-answer {
        padding: 8px 16px;
        font-size: 0.95rem;
    }
}
</style>

<script>
    // FAQ Toggle Script
    document.addEventListener('DOMContentLoaded', () => {
        const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            item.classList.toggle('open');
        });
    });
});
</script>

    <?php include("footer.php"); ?>
</body>
</html>