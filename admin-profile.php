<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header('Location: access-denied.php');
    exit();
}

$section = isset($_GET['section']) ? $_GET['section'] : 'products';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = floatval($_POST['price']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 1;
        $subcategory_id = isset($_POST['subcategory_id']) ? (int)$_POST['subcategory_id'] : 1;
        $image = 'default.jpg';

        if (!empty($_FILES['image']['name'])) {
            $image_name = basename($_FILES['image']['name']);
            $image_path = "Assets/" . $image_name;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                $image = $image_name;
            } else {
                echo "<script>alert('Image upload failed!');</script>";
            }
        }

        $sql = "INSERT INTO products (name, price, description, image, category_id, subcategory_id) 
                VALUES ('$name', $price, '$description', '$image', $category_id, $subcategory_id)";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Product added successfully!'); window.location.href='admin-profile.php?section=products';</script>";
        } else {
            echo "<script>alert('Database error: " . $conn->error . "');</script>";
        }
    }

    if (isset($_POST['delete_product'])) {
        $id = (int)$_POST['delete_product'];
        $conn->query("DELETE FROM products WHERE id = $id");
    }

    if (isset($_POST['add_user'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            $conn->query($sql);
        } else {
            echo "<script>alert('Password cannot be empty!');</script>";
        }
    }

    if (isset($_POST['delete_user'])) {
        $id = (int)$_POST['delete_user'];
        $conn->query("DELETE FROM users WHERE id = $id");
    }

    if (isset($_POST['update_order_status']) && isset($_POST['order_id']) && isset($_POST['new_status'])) {
        $order_id = (int)$_POST['order_id'];
        $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
        $conn->query("UPDATE orders SET order_status = '$new_status' WHERE id = $order_id");
    }
}

$result_products = $conn->query("SELECT * FROM products ORDER BY id DESC");
$result_users = $conn->query("SELECT * FROM users ORDER BY id DESC");
$result_messages = $conn->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
$result_orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Css/admin-profile.css">
</head>
<body>
    <div class="sidebar">
        <h3>Admin Panel</h3>
        <nav>
            <a href="?section=products" class="<?= $section == 'products' ? 'active' : '' ?>">Manage Products</a>
            <a href="?section=users" class="<?= $section == 'users' ? 'active' : '' ?>">Manage Users</a>
            <a href="?section=messages" class="<?= $section == 'messages' ? 'active' : '' ?>">View Messages</a>
            <a href="?section=orders" class="<?= $section == 'orders' ? 'active' : '' ?>">View Orders</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>

    <div class="main-contentP">
        <div id="products-section" style="display: <?= $section == 'products' ? 'block' : 'none' ?>;">
            <h2>Manage Products</h2>
            <button class="add-btn" onclick="toggleForm('addProductForm')">Add New Product</button>
            <div id="addProductForm" class="form-container" style="display:none;">
                <h3>Add Product</h3>
                <form method="POST" enctype="multipart/form-data" action="">
                    <label>Product Name</label>
                    <input type="text" name="name" required>
                    <label>Price (LKR)</label>
                    <input type="number" name="price" step="0.01" required>
                    <label>Description</label>
                    <textarea name="description" required></textarea>
                    <label>Image</label>
                    <input type="file" name="image">
                    <label>Category</label>
                    <select name="category_id" required>
                        <option value="1">Laptops</option>
                        <option value="2">Smartphones</option>
                        <option value="3">Accessories</option>
                    </select>
                    <label>Subcategory</label>
                    <select name="subcategory_id" required>
                        <option value="1">ASUS</option>
                        <option value="2">DELL</option>
                        <option value="3">HP</option>
                        <option value="4">APPLE</option>
                        <option value="5">SAMSUNG</option>
                    </select><br><br>
                    <button type="submit" name="add_product">Add Product</button>
                    <button type="button" onclick="toggleForm('addProductForm')">Cancel</button>
                </form>
            </div>
            <div class="table-container">
                <table>
                    <tr><th>Image</th><th>Name</th><th>Price</th><th>Description</th><th>Actions</th></tr>
                    <?php while ($product = $result_products->fetch_assoc()): ?>
                    <tr>
                        <td><img src="Assets/<?= $product['image']; ?>" width="50"></td>
                        <td><?= $product['name']; ?></td>
                        <td>LKR <?= number_format($product['price'], 2); ?></td>
                        <td><?= $product['description']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_product" value="<?= $product['id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="main-contentU">
        <div id="users-section" style="display: <?= $section == 'users' ? 'block' : 'none' ?>;">
            <h2>Manage Users</h2>
            <button class="add-btn" onclick="toggleForm('addUserForm')">Add New User</button>
            <div id="addUserForm" class="form-container" style="display:none;">
                <h3>Add/Edit User</h3>
                <form method="POST" action="">
                    <label>Username</label>
                    <input type="text" name="username" required>
                    <label>Email</label>
                    <input type="email" name="email" required>
                    <label>Password</label>
                    <input type="password" name="password" required>
                    <button type="submit" name="add_user">Add User</button>
                    <button type="button" onclick="toggleForm('addUserForm')">Cancel</button>
                </form>
            </div>
            <div class="table-container">
                <table>
                    <tr><th>Username</th><th>Email</th><th>Actions</th></tr>
                    <?php while ($user = $result_users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['username']; ?></td>
                        <td><?= $user['email']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_user" value="<?= $user['id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <!-- ✅ VIEW MESSAGES -->
        <div id="messages-section" style="display: <?= $section == 'messages' ? 'block' : 'none' ?>;">
            <h2>Customer Messages</h2>
            <div class="table-container">
                <table>
                    <tr><th>Name</th><th>Email</th><th>Message</th><th>Submitted At</th></tr>
                    <?php while ($msg = $result_messages->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($msg['name']); ?></td>
                        <td><?= htmlspecialchars($msg['email']); ?></td>
                        <td><?= nl2br(htmlspecialchars($msg['message'])); ?></td>
                        <td><?= $msg['submitted_at']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
    <!-- View Orders -->
<div class="main-contentU">
    <div id="orders-section" style="display: <?= $section == 'orders' ? 'block' : 'none' ?>;">
        <h2>All Orders</h2>
        <div class="table-container">
            <table>
                <tr><th>Order ID</th><th>Customer</th><th>Contact</th><th>Address</th><th>Total</th><th>Date</th><th>Status</th></tr>
                <?php while ($order = $result_orders->fetch_assoc()): ?>
                <tr>
                    <td>#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                    <td><?= htmlspecialchars($order['customer_name']); ?></td>
                    <td><?= htmlspecialchars($order['contact_number']); ?></td>
                    <td><?= htmlspecialchars($order['shipping_address']); ?></td>
                    <td>LKR <?= number_format($order['total_amount'], 2); ?></td>
                    <td><?= $order['order_date']; ?></td>
                    <td>
                        <form method="POST" style="display: flex; gap: 5px; align-items: center;">
                            <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                            <select name="new_status">
                                <option value="Pending" <?= $order['order_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Completed" <?= $order['order_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="Cancelled" <?= $order['order_status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_order_status">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>


    <script>
        function toggleForm(formId) {
            let form = document.getElementById(formId);
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }
    </script>
</body>
</html>
