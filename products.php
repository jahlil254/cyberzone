<?php
session_start();
require_once 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch categories and brands
$categoryQuery = "SELECT * FROM categories";
$categoryResult = $conn->query($categoryQuery);
$brandQuery = "SELECT * FROM subcategories";
$brandResult = $conn->query($brandQuery);

// Filter products
$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$selectedBrand = isset($_GET['brand']) ? (int)$_GET['brand'] : 0;

$sql = "SELECT products.*, categories.name AS category_name, subcategories.name AS brand_name FROM products 
        LEFT JOIN categories ON products.category_id = categories.id
        LEFT JOIN subcategories ON products.subcategory_id = subcategories.id";

$conditions = [];
if ($selectedCategory > 0) $conditions[] = "products.category_id = $selectedCategory";
if ($selectedBrand > 0) $conditions[] = "products.subcategory_id = $selectedBrand";
if (!empty($conditions)) $sql .= " WHERE " . implode(" AND ", $conditions);

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberZone | Products</title>
    <link rel="stylesheet" href="Css/products.css">
    <link rel="stylesheet" href="Css/index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <br><br>

    <section class="storefront">
        <div class="container">
            <br>
            <form method="GET" action="products.php" class="filter-controls">
                <label for="category">Select Category:</label>
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="0">All Categories</option>
                    <?php while($category = $categoryResult->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo ($selectedCategory == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <label for="brand">Choose a Brand:</label>
                <select name="brand" id="brand" onchange="this.form.submit()">
                    <option value="0">All Brands</option>
                    <?php while($brand = $brandResult->fetch_assoc()): ?>
                        <option value="<?php echo $brand['id']; ?>" <?php echo ($selectedBrand == $brand['id']) ? 'selected' : ''; ?>>
                            <?php echo $brand['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>
            
            <div class="product-showcase">
                <?php while($product = $result->fetch_assoc()): ?>
                    <div class="item-card">
                        <div class="item-image">
                            <img src="Assets/<?php echo basename($product['image']); ?>" alt="<?php echo $product['name']; ?>">
                        </div>
                        <div class="item-details">
                            <h3><?php echo $product['name']; ?></h3>
                        <!--<p class="info">Category: <?php echo $product['category_name']; ?></p>-->
                            <p class="info">Brand: <?php echo $product['brand_name']; ?></p>
                            <p class="info"><?php echo $product['description']; ?></p>
                            <div class="cost">LKR: <?php echo number_format($product['price'], 2); ?>/=</div>
                            <div class="purchase-options">
                                <?php if(isset($_SESSION['username'])): ?>
                                    <button class="cart-btn" onclick='addToCart(<?php echo json_encode([
                                        "id" => $product["id"],
                                        "name" => $product["name"],
                                        "price" => $product["price"],
                                        "image" => basename($product["image"])
                                    ]); ?>)'>
                                        <i class='bx bx-cart-add'></i> Add to Cart
                                    </button>
                                <?php else: ?>
                                    <button class="cart-btn disabled" onclick="promptLogin()">
                                        <i class='bx bx-cart-add'></i> Add to Cart
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <script>
        function addToCart(product) {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            
            // Check if product already exists
            let existingProduct = cart.find(item => item.id === product.id);
            if (existingProduct) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Item Already in Cart',
                    text: 'This item is already in your cart.',
                    confirmButtonColor: '#ff9f1c'
                });
                return;
            }

            cart.push(product);
            localStorage.setItem("cart", JSON.stringify(cart));
            updateCart();

            Swal.fire({
                icon: 'success',
                title: 'Added to Cart!',
                text: `${product.name} has been added to your cart.`,
                confirmButtonColor: '#28a745'
            });
        }

        function removeFromCart(index) {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            cart.splice(index, 1);
            localStorage.setItem("cart", JSON.stringify(cart));
            updateCart();
        }

        function updateCart() {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            const cartItemsContainer = document.querySelector('.cart-items');
            const cartCount = document.querySelector('.cart-count');
            const totalAmount = document.querySelector('.total-amount');

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
        }

        function promptLogin() {
            Swal.fire({
                icon: 'error',
                title: 'Login Required',
                text: 'You must log in to add items to your cart.',
                confirmButtonColor: '#dc3545'
            });
        }

        window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 0) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

    </script>

    <style>
        .disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</body>
</html>
