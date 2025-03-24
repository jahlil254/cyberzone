<!-- Navigation -->
<link rel="stylesheet" href="Css/navbar.css">
<nav class="navbar" id="navbar">
    <div class="nav-brand">
        <img src="Assets/logo1.png" alt="CyberZone" id="navLogo">
    </div>

    <div class="nav-toggle" id="navToggle">
        <i class='bx bx-menu'></i>
    </div>

    <div class="nav-links">
        <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a>
        <a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">Products</a>
        <a href="contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact Us</a>
    </div>

    <div class="nav-auth">
        <?php if(isset($_SESSION['username'])): ?>
            <a href="user-profile.php" class="username">Welcome, <?php echo $_SESSION['username']; ?></a>
            
            <!-- Show cart icon only on products.php -->
            <?php if (basename($_SERVER['PHP_SELF']) == 'products.php'): ?>
            <div class="cart-icon" id="cartIcon">
                <i class='bx bx-cart'></i>
                <span class="cart-count">0</span>
            </div>
            <div class="cart-popup" id="cartPopup">
                <div class="cart-popup-header">
                    <h3>Shopping Cart</h3>
                    <span class="close-cart" id="closeCart">×</span>
                </div>
                <div class="cart-items"></div>
                <div class="cart-total">Total: LKR: <span class="total-amount">0.00</span></div>
                <form id="paymentForm" action="payment.php" method="POST">
                    <input type="hidden" name="cart_items" id="cartItemsInput">
                    <input type="hidden" name="total_amount" id="totalAmountInput">
                    <button type="submit" class="buy-now-btn">Buy Now</button>
                </form>
            </div>
            <?php endif; ?>
            
            <a href="logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
            <a href="login.php" class="login-btn">Login</a>
        <?php endif; ?>
    </div>    
</nav>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');
    const navLogo = document.getElementById('navLogo');
    const defaultLogo = 'Assets/logo1.png';
    const scrollLogo = 'Assets/logo2.png';

    const navToggle = document.getElementById('navToggle');
    const navLinks = document.querySelector('.nav-links');
    const cartIcon = document.getElementById('cartIcon');
    const cartPopup = document.getElementById('cartPopup');
    const closeCart = document.getElementById('closeCart');
    const cartItemsContainer = document.querySelector('.cart-items');
    const cartCount = document.querySelector('.cart-count');
    const totalAmount = document.querySelector('.total-amount');

    // Load cart from localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Mobile menu toggle
    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // Show cart on click
    if (cartIcon) {
        cartIcon.addEventListener('click', () => {
            cartPopup.classList.toggle('active');
            updateCart();
        });
    }

    if (closeCart) {
        closeCart.addEventListener('click', () => {
            cartPopup.classList.remove('active');
        });
    }

    function updateCart() {
        cartItemsContainer.innerHTML = "";
        let total = 0;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = "<p class='empty-cart'>Your cart is empty</p>";
        } else {
            cart.forEach((item, index) => {
                total += parseFloat(item.price);
                cartItemsContainer.innerHTML += `
                    <div class="cart-item" data-index="${index}">
                        <img src="Assets/${item.image}" alt="${item.name}" class="cart-item-image">
                        <div class="cart-item-details">
                            <h4>${item.name}</h4>
                            <span class="cart-item-price">LKR: ${parseFloat(item.price).toFixed(2)}</span>
                            <button class="remove-item" onclick="removeFromCart(${index})">Remove</button>
                        </div>
                    </div>
                `;
            });
        }

        cartCount.textContent = cart.length;
        totalAmount.textContent = total.toFixed(2);
        document.getElementById('cartItemsInput').value = JSON.stringify(cart);
        document.getElementById('totalAmountInput').value = total.toFixed(2);
    }

    function addToCart(product) {
        <?php if (isset($_SESSION['username'])): ?>
            cart.push(product);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCart();
        <?php else: ?>
            alert('Please log in to add items to your cart.');
        <?php endif; ?>
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCart();
    }

    document.addEventListener('click', (e) => {
        if (!cartPopup.contains(e.target) && !cartIcon.contains(e.target)) {
            cartPopup.classList.remove('active');
        }
    });

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
            navLogo.src = scrollLogo;
        } else {
            navbar.classList.remove('scrolled');
            navLogo.src = defaultLogo;
        }
    });

    // Ensure cart updates on page load
    updateCart();
});


</script>
