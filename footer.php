<!-- Footer Section -->
 <footer>
    <div class="footer-content">
        <ul>
            <li><a href="faqs.php">FAQs</a></li>
            <li><a href="return-exchange.php">RETURN & EXCHANGE</a></li>
            <li><a href="contact-us.php">CONTACT US</a></li>
            <li><a href="privacy-policy.php">PRIVACY POLICY</a></li>
            <li><a href="terms-conditions.php">TERMS & CONDITIONS</a></li>
        </ul>
         
        <div class="newsletter">
            <h2>NEWSLETTER</h2>
            <input type="email" placeholder="Enter email">
        </div>
        
        <div class="footer-logo">
            <img src="images/logo.png" alt="DOLLARSIGN Logo">
        </div>
        
        <div class="social-links">
            <a href="https://web.facebook.com/profile.php?id=61550540038795" target="_blank"><i class="fa-brands fa-facebook"></i></a>
            <a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" target="_blank"><i class="fa-brands fa-twitter"></i></a>
            <a href="#" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
        </div>
    </div>

    <style>
/* Footer Section */
footer {
  background-color: #111111;
  background-size: cover;
  color: white;
  text-align: center;
  padding: 40px 0;
  font-family: Arial, sans-serif;
  font-size: 12px;
  font-weight: normal;
  transition: padding 0.3s ease;
}

/* Legal Documents */
.footer-content ul {
  list-style: none;
  padding: 0;
  margin-bottom: 40px;
}

.footer-content ul li {
  margin: 15px 0;
  transition: color 0.2s ease;
}

.footer-content ul li a {
  color: #fff;
  text-decoration: none;
  transition: color 0.2s ease;
}

.footer-content ul li a:hover {
  color: #aaa;
}

/* Newsletter */
.newsletter {
  margin-bottom: 40px;
}

.newsletter h2 {
  font-weight: bold;
  margin-bottom: 0;
  font-size: 16px;
}

.newsletter input {
  padding: 15px 20px;
  border-radius: 3px;
  border: none;
  margin-top: 10px;
  width: 280px;
  max-width: 90%;
  font-size: 14px;
  transition: box-shadow 0.3s ease;
}

.newsletter input:focus {
  outline: none;
  box-shadow: 0 0 5px #ffffff88;
}

/* Footer Logo */
.footer-logo {
  filter: invert(1);
  margin-top: 30px;
  margin-bottom: 10px;
}

.footer-logo img {
  width: 250px;
  height: auto;
  transition: transform 0.3s ease;
}

.footer-logo img:hover {
  transform: scale(1.03);
}

/* Social Links */
.social-links a {
  color: white;
  font-size: 24px;
  margin: 0 10px;
  text-decoration: none;
  transition: color 0.2s ease;
}

.social-links a:hover {
  color: #aaa;
}

/* Responsiveness */
@media (max-width: 768px) {
  .footer-logo img {
    width: 180px;
  }

  .newsletter h2 {
    font-size: 14px;
  }

  .newsletter input {
    font-size: 13px;
    padding: 12px 16px;
  }

  .social-links a {
    font-size: 20px;
    margin: 0 8px;
  }
}

@media (max-width: 480px) {
  footer {
    padding: 30px 0;
  }

  .footer-content ul li {
    margin: 10px 0;
  }

  .footer-logo img {
    width: 160px;
  }

  .newsletter input {
    font-size: 12px;
    padding: 10px 14px;
  }

  .social-links a {
    font-size: 18px;
    margin: 0 6px;
  }
}
    </style>
</footer>