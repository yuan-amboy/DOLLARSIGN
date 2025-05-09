<?php
session_start();
$isLoggedIn = isset($_SESSION["users"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return & Exchange | DOLLARSIGN</title>
    <link rel="stylesheet" href="main.css">
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php include("navbar.php"); ?>

    <!-- Return & Exchange Section -->
    <div class="return-exchange-section">
        <h2>RETURN AND EXCHANGE</h2>

        <?php
        $returnItems = [
            ["title" => "1. GENERAL INFORMATION", "content" => "At Dollar Sign Clothing, we want you to love what you ordered! If you're not completely satisfied with your purchase, we're here to help. We offer returns and exchanges within 30 days of the delivery date."],
            ["title" => "2. ELIGIBILITY FOR RETURN/EXCHANGE", "content" => "<strong>Returns:</strong> Items may be returned within 30 days from the date of delivery. To be eligible for a return, the item must be in new, unworn, unwashed condition with all original tags and packaging intact.<br><br><strong>Exchanges:</strong> If you'd like to exchange an item for a different size or colour, we will be happy to assist you as long as the item meets the eligibility criteria above."],
            ["title" => "3. NON-RETURNABLE/NON-EXCHANGEABLE ITEMS", "content" => "<p>The following items are not eligible for return or exchange:</p><ul><li>Final sale or clearance items</li><li>Items purchased from third-party retailers (unless specified)</li><li>Gift cards</li></ul>"],
            ["title" => "4. HOW TO RETURN AN ITEM", "content" => "<p>To initiate a return, please follow these steps:</p><ul><li>Contact our customer support team at <strong>dollarsign.clothing@gmail.com</strong> with your order number and details of the item you'd like to return.</li><li>You will receive a return authorisation along with instructions on how to send your item back to us.</li><li>Pack the item securely in its original packaging (if possible) and include a copy of the receipt or proof of purchase.</li><li>Ship the item to the address provided by our customer support team. Please note that customers are responsible for return shipping costs unless the return is due to a mistake on our part (e.g., wrong item sent).</li></ul>"],
            ["title" => "5. REFUNDS", "content" => "<p>Once your returned item is received and inspected, we will process your refund to the original payment method. Please note:</p><ul><li>Refunds will exclude any original shipping charges unless the return is due to an error on our part.</li><li>Depending on your bank or payment provider, it may take 5-10 business days for the refund to appear in your account.</li></ul>"],
            ["title" => "6. HOW TO EXCHANGE AN ITEM", "content" => "If you would like to exchange an item, please follow the same process as a return. Once we receive your returned item, we will process the exchange and ship the new item to you. If the item you want to exchange for is out of stock, we will notify you and offer an alternative solution, such as a store credit or refund."],
            ["title" => "7. DAMAGED OR DEFECTIVE ITEMS", "content" => "If you receive a damaged or defective item, please contact us within 7 days of delivery with a photo of the damage/defect. We will gladly assist you with a return or exchange, and we will cover the return shipping costs for faulty items."],
            ["title" => "8. SALE ITEMS", "content" => "Sale items can be returned or exchanged if they meet the eligibility criteria. However, sale items marked as 'Final Sale' are non-returnable and non-exchangeable."],
            ["title" => "9. CONTACT INFORMATION", "content" => "If you have any questions or concerns regarding your return or exchange, please don't hesitate to contact us at:<ul><li><strong>Email:</strong> dollarsign.clothing@gmail.com</li><li><strong>Facebook:</strong><a href='https://web.facebook.com/profile.php?id=61550540038795' target='_blank' class='dollarsign-facebook-link'> Dollar SIGN Clothing</a></li></ul>"],
        ];

        foreach ($returnItems as $item) {
            echo '<div class="return-exchange-item">';
            echo '<div class="return-exchange-title">' . $item['title'] . '</div>';
            echo '<div class="return-exchange-content">' . $item['content'] . '</div>';
            echo '</div>';
        }
        ?>
    </div>

<style>
/* Return & Exchange Section */
.return-exchange-section {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
}

.return-exchange-section h2 {
    font-family: 'Arial', sans-serif;
    font-size: 2.8rem;
    margin: 80px 0px 25px;
    text-align: center;
}

/* Item Wrapper */
.return-exchange-item {
    border-bottom: 1px solid #ccc;
    overflow: hidden;
    transition: max-height 0.4s ease, opacity 0.3s ease;
}

/* Title/Question */
.return-exchange-title {
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

.return-exchange-title:hover {
    background: #e8e8e8;
}

.return-exchange-title::after {
    content: '+';
    font-size: 24px;
    transition: transform 0.3s ease;
}

/* Content/Answer */
.return-exchange-content {
    padding: 10px 20px;
    background-color: rgb(245, 245, 245);
    display: none;
    opacity: 0;
    text-align: justify;
    line-height: 1.5;
    animation: fadeIn 0.3s ease-in-out forwards;
}

.return-exchange-item.open .return-exchange-content {
    display: block;
    opacity: 1;
}

.return-exchange-item.open .return-exchange-title::after {
    content: '-';
    transform: rotate(180deg);
}

/* Fade In Animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Responsive Styles */
@media (max-width: 768px) {
    .return-exchange-section {
        padding: 15px;
        margin: 30px 15px;
    }

    .return-exchange-section h2 {
        font-size: 2rem;
    }

    .return-exchange-title {
        padding: 12px 16px;
    }

    .return-exchange-content {
        padding: 8px 16px;
        font-size: 0.95rem;
    }
}

</style>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.return-exchange-title').forEach(item => {
            item.addEventListener('click', () => {
                const parent = item.parentElement; 
                parent.classList.toggle('open');
            });
        });
        });
        </script>

    <?php include("footer.php"); ?>
</body>
</html>
