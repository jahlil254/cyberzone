<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberZone | Home</title>
    <link rel="stylesheet" href="Css/index.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
        <div class="hero-text">
    <h1>Welcome to CyberZone</h1>
    <p>Discover the latest laptops, smartphones, and accessories at unbeatable prices.<br> Explore top brands, cutting-edge technology, and exclusive deals.</p>
</div>
            <a href="products.php" class="cta-btn">Shop Now</a>
        </div>
    </section>

    <!-- Featured Items -->
    <section id="featured" class="featured">
        <h2>Featured Items</h2>
        <div class="products-grid">
            <div class="product-card">
                <img src="Assets/ar.png" alt="ROG">
                <h3>ASUS ROG</h3>
                <p>The ASUS ROG Strix G15 is a high-performance gaming laptop featuring powerful hardware.</p>
                <span class="price">LKR: 410,000.00/=</span>
                <button class="add-to-cart">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="Assets/ip16.png" alt="Cappuccino">
                <h3>Iphone 16 Pro Max</h3>
                <p>It features a 6.9-inch display, A18 Pro chip,and a new Camera Control button.</p>
                <span class="price">LKR: 375,000.00/=</span>
                <button class="add-to-cart">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="Assets/s25.png" alt="Matcha latte">
                <h3>Samsung S25 Ultra</h3>
                <p> Samsung Galaxy S25 Ultra has a Snapdragon 8 Gen 4 chip,200MP camera,and an improved S Pen.</p>
                <span class="price">LKR:325,000.00/=</span>
                <button class="add-to-cart">Add to Cart</button>
            </div>
            <div class="product-card">
                <img src="Assets/ap.png" alt="Cafe Mocha">
                <h3>Airpods</h3>
                <p>AirPods feature seamless connectivity, high-quality audio.</p>
                <span class="price">LKR: 40,000.00/=</span>
                <button class="add-to-cart">Add to Cart</button>
            </div>  
        </div>
    </section>

   
    <!-- Customer Feedback Section -->
    <section class="feedback-section">
        <h2>What Our Customers Say</h2>
        <div class="feedback-container">
            <div class="feedback-card">
                <div class="customer-image">
                    <img src="Assets/2.png" alt="Sarah Johnson">
                </div>
                <div class="customer-info">
                    <h3>Sarah Johnson</h3>
                    <div class="rating">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>"Highly satisfied with my purchase. The staff was very helpful in choosing the right gadget! "</p>
                </div>
            </div>

            <div class="feedback-card">
                <div class="customer-image">
                    <img src="Assets/7.png" alt="Michael Chen">
                </div>
                <div class="customer-info">
                    <h3>Michael Chen</h3>
                    <div class="rating">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star-half'></i>
                    </div>
                    <p>"Great after-sales service. Helped me set up my new laptop without any hassle."</p>
                </div>
            </div>


            <div class="feedback-card">
                <div class="customer-image">
                    <img src="Assets/4.png" alt="Ethan Clarke">
                </div>
                <div class="customer-info">
                    <h3>Ethan Clarke</h3>
                    <div class="rating">
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                        <i class='bx bxs-star'></i>
                    </div>
                    <p>"A must-visit for tech lovers! Amazing variety and top-notch quality."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-section">
                <img src="Assets/logo1.png" alt="Sips of Serenity">
                <p>Your one-stop shop for the latest electronics, gadgets, and accessories. We offer top brands at unbeatable prices with excellent customer service.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="contact.php">Contact Us</a>
            </div>
            <div class="footer-section">
                <h3>Opening Hours</h3>
                <p>Monday - Friday: 9am - 9pm</p>
                <p>Saturday - Sunday: 9am - 6pm</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-instagram'></i></a>
                    <a href="#"><i class='bx bxl-twitter'></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 CyberZone. All rights reserved.</p>
        </div>
    </footer>
    <script>
        const navLogo = document.getElementById('navLogo');
            const defaultLogo = 'Assets/logo1.png';
            const scrollLogo = 'Assets/logo2.png';

            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navLogo.src = scrollLogo;
                } else {
                    navLogo.src = defaultLogo;
                }
            });
        document.addEventListener('DOMContentLoaded', () => {
            // Mobile Menu Toggle
            const navToggle = document.getElementById('navToggle');
            const navLinks = document.querySelector('.nav-links');

            navToggle.addEventListener('click', () => {
                navLinks.classList.toggle('active');
            });

            // Cart Popup Functionality
            const cartIcon = document.querySelector('.cart-icon');
            const cartPopup = document.querySelector('.cart-popup');
            const closeCart = document.querySelector('.close-cart');
            const cartItems = document.querySelector('.cart-items');
            const cartCount = document.querySelector('.cart-count');
            const totalAmount = document.querySelector('.total-amount');
            let cartProducts = [];
            let count = 0;

            cartIcon.addEventListener('click', () => {
                cartPopup.classList.toggle('active');
            });

            closeCart.addEventListener('click', () => {
                cartPopup.classList.remove('active');
            });

            // Cart Functionality
            const cartBtns = document.querySelectorAll('.add-to-cart');

            cartBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const productCard = btn.closest('.product-card');
                    const productName = productCard.querySelector('h3').textContent;
                    const productPrice = productCard.querySelector('.price').textContent;
                    const productImage = productCard.querySelector('img').src;

                    count++;
                    cartCount.textContent = count;

                    // Add item to cart array
                    cartProducts.push({
                        name: productName,
                        price: productPrice,
                        image: productImage
                    });

                    // Update cart display
                    updateCartDisplay();

                    btn.textContent = 'Added to Cart';
                    btn.disabled = true;
                    setTimeout(() => {
                        btn.textContent = 'Add to Cart';
                        btn.disabled = false;
                    }, 2000);
                });
            });

            function updateCartDisplay() {
                if (cartProducts.length === 0) {
                    cartItems.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
                    totalAmount.textContent = '0.00';
                    document.querySelector('.buy-now-btn').disabled = true;
                    return;
                }

                let total = 0;
                cartItems.innerHTML = cartProducts.map((item, index) => {
                    const price = parseFloat(item.price.replace('$', ''));
                    total += price;
                    return `
                        <div class="cart-item">
                            <img src="${item.image}" alt="${item.name}">
                            <div class="cart-item-details">
                                <h4>${item.name}</h4>
                                <span class="cart-item-price">${item.price}</span>
                            </div>
                        </div>
                    `;
                }).join('');

                totalAmount.textContent = total.toFixed(2);
    
                // Update hidden form inputs
                document.getElementById('cartItemsInput').value = JSON.stringify(cartProducts);
                document.getElementById('totalAmountInput').value = total.toFixed(2);
                document.querySelector('.buy-now-btn').disabled = false;
            }
            // Close cart popup when clicking outside
            document.addEventListener('click', (e) => {
                if (!cartPopup.contains(e.target) && !cartIcon.contains(e.target)) {
                    cartPopup.classList.remove('active');
                }
            });

            // Rest of the existing script remains the same
        });

        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        function proceedToPayment() {
            if (cartProducts.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            
            const cartData = {
                items: cartProducts,
                total: document.querySelector('.total-amount').textContent
            };

            // Create form and submit to payment page
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'payment.php';

            const cartInput = document.createElement('input');
            cartInput.type = 'hidden';
            cartInput.name = 'cart_items';
            cartInput.value = JSON.stringify(cartData.items);
            form.appendChild(cartInput);

            const totalInput = document.createElement('input');
            totalInput.type = 'hidden';
            totalInput.name = 'total_amount';
            totalInput.value = cartData.total;
            form.appendChild(totalInput);

            document.body.appendChild(form);
            form.submit();
        }

    </script>
</body>
</html>
