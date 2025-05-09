<?php
session_start();
require_once "database.php";

$isLoggedIn = isset($_SESSION["users"]);

$popupShown = isset($_SESSION['popup_shown']) ? $_SESSION['popup_shown'] : false;

if (!$popupShown) {
    $_SESSION['popup_shown'] = true;
    $showPopup = true;
} else {
    $showPopup = false;
}

$verificationMessage = isset($_SESSION['verification_message']) ? $_SESSION['verification_message'] : null;
unset($_SESSION['verification_message']);
?>

<?php if ($showPopup): ?>
    <div id="popupAd" class="popup">
        <div class="popup-content">
            <h2>FREE SHIPPING</h2>
            <p>Get it Delivered Free</p>
            <p>Anywhere in the Philippines</p>
            <button id="closePopup">Close</button>
        </div>
    </div>
<?php endif; ?>

<?php if ($verificationMessage): ?>
    <div id="verificationPopup" class="popup-verification">
        <div class="popup-verification-content">
            <p><?= htmlspecialchars($verificationMessage) ?></p>
        </div>
    </div>
<?php endif; ?>

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

    <!-- Banner Section -->
    <main>
  <section class="banner">
    <img src="images/banner.jpg" alt="Banner">
    <div class="banner-text">
      <h1>GET MONEY TO LEAVE CHAOS</h1>
      <a href="shop.php" class="shop-button">SHOP NOW</a>
    </div>
  </section>
</main>

    <!-- New Arrival Section -->
    <section class="new-arrival">
        <div class="new-arrival-header">
            <h2>NEW ARRIVAL</h2>
        </div>
        <div class="new-arrival-container" id="newArrivalSection">
            <!-- New arrival products will be dynamically loaded here -->
        </div>
    </section>

    <section class="infinite-slider">
        <div class="slider-track">
            <img src="images/index-dollarsign01.jpg" alt="">
            <img src="images/index-dollarsign02.jpg" alt="">
            <img src="images/index-dollarsign03.jpg" alt="">
            <img src="images/index-dollarsign04.jpg" alt="">

            <img src="images/index-dollarsign01.jpg" alt="">
            <img src="images/index-dollarsign02.jpg" alt="">
            <img src="images/index-dollarsign03.jpg" alt="">
            <img src="images/index-dollarsign04.jpg" alt="">

            <img src="images/index-dollarsign01.jpg" alt="">
            <img src="images/index-dollarsign02.jpg" alt="">
            <img src="images/index-dollarsign03.jpg" alt="">
            <img src="images/index-dollarsign04.jpg" alt="">

            <img src="images/index-dollarsign01.jpg" alt="">
            <img src="images/index-dollarsign02.jpg" alt="">
            <img src="images/index-dollarsign03.jpg" alt="">
            <img src="images/index-dollarsign04.jpg" alt="">

            <img src="images/index-dollarsign01.jpg" alt="">
            <img src="images/index-dollarsign02.jpg" alt="">
            <img src="images/index-dollarsign03.jpg" alt="">
            <img src="images/index-dollarsign04.jpg" alt="">

            <img src="images/index-dollarsign01.jpg" alt="">
            <img src="images/index-dollarsign02.jpg" alt="">
            <img src="images/index-dollarsign03.jpg" alt="">
            <img src="images/index-dollarsign04.jpg" alt="">
        </div>
    </section>

    <section class="infinite-slider reverse">
  <div class="slider-track reverse-track">
    <img src="images/index-dollarsign05.jpg" alt="">
    <img src="images/index-dollarsign06.jpg" alt="">
    <img src="images/index-dollarsign07.jpg" alt="">

    <img src="images/index-dollarsign05.jpg" alt="">
    <img src="images/index-dollarsign06.jpg" alt="">
    <img src="images/index-dollarsign07.jpg" alt="">

    <img src="images/index-dollarsign05.jpg" alt="">
    <img src="images/index-dollarsign06.jpg" alt="">
    <img src="images/index-dollarsign07.jpg" alt="">

    <img src="images/index-dollarsign05.jpg" alt="">
    <img src="images/index-dollarsign06.jpg" alt="">
    <img src="images/index-dollarsign07.jpg" alt="">

    <img src="images/index-dollarsign05.jpg" alt="">
    <img src="images/index-dollarsign06.jpg" alt="">
    <img src="images/index-dollarsign07.jpg" alt="">

    <img src="images/index-dollarsign05.jpg" alt="">
    <img src="images/index-dollarsign06.jpg" alt="">
    <img src="images/index-dollarsign07.jpg" alt="">

    <img src="images/index-dollarsign05.jpg" alt="">
    <img src="images/index-dollarsign06.jpg" alt="">
    <img src="images/index-dollarsign07.jpg" alt="">
  </div>
</section>

    <?php include("footer.php"); ?>

    <style>
/* Infinite Horizontal Slider */
.infinite-slider {
  overflow: hidden;
  background: #111111;
  width: 100%;
  padding: 20px;
  box-sizing: border-box;
}

.infinite-slider.reverse {
  padding-top: 0px;
}

.slider-track,
.reverse-track {
  display: flex;
  width: calc(300px * 12);
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}

.slider-track {
  animation-name: scroll;
  animation-duration: 30s;
}

.reverse-track {
  animation-name: scroll-reverse;
  animation-duration: 30s;
}

.slider-track img,
.reverse-track img {
  width: 300px;
  height: auto;
  margin-right: 10px;
  flex-shrink: 0;
  object-fit: cover;
  border-radius: 3px;
  transition: transform 0.3s ease;
}

.slider-track img:hover,
.reverse-track img:hover {
  transform: scale(1.05);
}

/* Keyframes */
@keyframes scroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-1800px);
  }
}

@keyframes scroll-reverse {
  0% {
    transform: translateX(-1800px);
  }
  100% {
    transform: translateX(0);
  }
}

/* Responsiveness */
@media (max-width: 1024px) {
  .slider-track,
  .reverse-track {
    width: calc(220px * 12);
  }

  .slider-track img,
  .reverse-track img {
    width: 220px;
  }

  @keyframes scroll {
    0% {
      transform: translateX(0);
    }
    100% {
      transform: translateX(-1320px);
    }
  }

  @keyframes scroll-reverse {
    0% {
      transform: translateX(-1320px);
    }
    100% {
      transform: translateX(0);
    }
  }
}

@media (max-width: 600px) {
  .slider-track,
  .reverse-track {
    width: calc(160px * 12);
  }

  .slider-track img,
  .reverse-track img {
    width: 160px;
  }

  @keyframes scroll {
    0% {
      transform: translateX(0);
    }
    100% {
      transform: translateX(-960px);
    }
  }

  @keyframes scroll-reverse {
    0% {
      transform: translateX(-960px);
    }
    100% {
      transform: translateX(0);
    }
  }
}
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const closePopup = document.getElementById('closePopup');
        if (closePopup) {
            closePopup.addEventListener('click', () => {
                document.getElementById('popupAd').style.display = 'none';
            });
        }
        
        const verificationPopup = document.getElementById('verificationPopup');
        if (verificationPopup) {
            setTimeout(() => {
                verificationPopup.classList.add('fade-out');
                setTimeout(() => {
                    verificationPopup.style.display = 'none';
            }, 500);
        }, 2000);
    }
});
    </script>
</body>
</html>