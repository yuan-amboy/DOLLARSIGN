<!-- Recommended Products Section -->
<div class="recommendation-container">
    <h2>RECOMMENDATION</h2>
    <div class="recommendation-grid" id="recommendations">
        <!-- Recommended products will be inserted here -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const productName = "<?php echo htmlspecialchars($productName, ENT_QUOTES, 'UTF-8'); ?>";
    let currentProduct = null;

    if (typeof products !== 'undefined') {
        currentProduct = products.find(p => p.name === productName);

        if (currentProduct) {
            document.getElementById('product-name').textContent = currentProduct.name;
            document.getElementById('product-price').textContent = currentProduct.price;
            document.getElementById('product-description').textContent = currentProduct.description;
            
            const productImage = document.getElementById('product-image');
            productImage.src = currentProduct.imageFront;

            productImage.addEventListener('mouseover', () => {
                productImage.src = currentProduct.imageBack;
            });

            productImage.addEventListener('mouseout', () => {
                productImage.src = currentProduct.imageFront;
            });
        }

        generateRecommendations(currentProduct);
    } else {
        console.error("Error: 'products' is not defined.");
    }
});

function generateRecommendations(currentProduct) {
    if (!products || products.length === 0) return;

    // Filter out the current product
    let filteredProducts = products.filter(p => p.name !== currentProduct.name);

    // Shuffle and pick 4 random products
    let shuffled = filteredProducts.sort(() => 0.5 - Math.random());
    let recommended = shuffled.slice(0, 4);

    // Insert into the DOM
    const recommendationsContainer = document.getElementById('recommendations');
    recommendationsContainer.innerHTML = recommended.map(p => `
        <div class="recommended-item">
            <a href="product.php?product=${encodeURIComponent(p.name)}">
                <img src="${p.imageFront}" alt="${p.name}" 
                    onmouseover="this.src='${p.imageBack}'" 
                    onmouseout="this.src='${p.imageFront}'">
                <p class="recommended-title">${p.name}</p>
                <p class="recommended-price">${p.price}</p>
            </a>
        </div>
    `).join('');
}
</script>

<style>
.recommendation-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 50px 20px 80px;
    margin: 0 auto;
    max-width: 1200px;
    box-sizing: border-box;
}

.recommendation-container h2 {
    font-family: 'Arial', sans-serif;
    font-size: 50px;
    padding: 0;
    color: #000000;
    position: relative;
    display: inline-block;
    margin: 0;
}

.recommendation-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 50px 20px 80px;
    margin: 0 auto;
    max-width: 1200px;
    box-sizing: border-box;
}

.recommended-item {
    background-color: #111111;
  padding: 10px;
  width: 100%;
  max-width: 230px;
  transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  border: 1px solid #222;
  overflow: hidden;
}

.recommended-item:hover {
    transform: scale(1.05);
    background-color: #1a1a1a;
}

.recommended-item a {
    flex: 0 1 calc(25% - 20px);
    box-sizing: border-box;
    max-width: calc(25% - 20px);
    min-width: 250px;
    transition: transform 0.3s ease;
}

.recommended-item img {
    width: 100%;
    max-width: 200px;
    height: 200px;
    margin: 0 auto 0;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.recommended-item:hover img {
    transform: scale(1.02);
}

.recommended-title, .recommended-price {
    font-family: 'Arial', sans-serif;
  font-size: 14px;
  font-weight: bold;
  color: #ffffff;
  transition: color 0.3s ease, text-shadow 0.3s ease;
}

.recommended-title {
    white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin: 10px 0px;
  max-width: 100%;
}

.recommended-price {
    margin: 0 !important;
}

a {
    text-decoration: none !important;
    color: inherit !important;
}

/* Add to Cart Button for Recommendations */
.recommended-add-to-cart {
    background: #333;
    color: white;
    border: none;
    padding: 8px 15px;
    font-size: 14px;
    cursor: pointer;
    margin-top: 10px;
    transition: all 0.3s ease;
    width: 80%;
    opacity: 0;
    transform: translateY(10px);
}

.recommended-item:hover .recommended-add-to-cart {
    opacity: 1;
    transform: translateY(0);
}

.recommended-add-to-cart:hover {
    background: #000;
}

/* Tablet Styles */
@media (max-width: 991px) {
    .recommendation-container h2 {
        font-size: 36px;
    }
    
    .recommendation-grid {
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 20px;
    }
    
    .recommended-item {
        max-width: 210px;
    }
    
    .recommended-item img {
        height: 180px;
    }
}

/* Mobile Styles */
@media (max-width: 767px) {
    .recommendation-container {
        padding: 25px 15px;
        margin-top: 40px;
    }
    
    .recommendation-container h2 {
        font-size: 30px;
        margin-bottom: 15px;
    }
    
    .recommendation-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
        padding: 10px;
    }
    
    .recommended-item {
        max-width: 160px;
        padding: 10px;
    }
    
    .recommended-item img {
        height: 150px;
        margin-bottom: 10px;
    }
    
    .recommended-title {
        font-size: 14px;
    }
    
    .recommended-price {
        font-size: 14px;
    }
    
    .recommended-add-to-cart {
        opacity: 1;
        transform: translateY(0);
        width: 90%;
        padding: 6px 10px;
        font-size: 13px;
    }
}

/* Small Mobile Styles */
@media (max-width: 480px) {
    .recommendation-container h2 {
        font-size: 26px;
    }
    
    .recommendation-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .recommended-item {
        max-width: 100%;
    }
    
    .recommended-item img {
        height: 120px;
    }
}
</style>