<?php
session_start();
require_once 'db.php';

if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit();
}

$order_id = $_GET['order_id'];
$order_sql = "SELECT * FROM orders WHERE id = $order_id";
$order_result = $conn->query($order_sql);
$order = $order_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberZone | Order Confirmation</title>
    <link rel="stylesheet" href="Css/order_confirmation.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="confirmation-container">
        <div class="success-icon">
            <i class='bx bx-check-circle'></i>
        </div>
        
        <h1>Order Confirmed!</h1>
        <p class="thank-you">Thank you for your order, <?php echo $order['customer_name']; ?>!</p>
        
        <div class="order-info">
            <div class="info-item">
                <i class='bx bx-receipt'></i>
                <span>Order ID: #<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="info-item">
                <i class='bx bx-calendar'></i>
                <span>Order Date: <?php echo date('F j, Y', strtotime($order['order_date'])); ?></span>
            </div>
            <div class="info-item">
                <i class='bx bx-dollar-circle'></i>
                <span>Total Amount: LKR <?php echo number_format($order['total_amount'], 2); ?></span>
            </div>
        </div>

        <div class="order-details">
            <h2>Order Details</h2>
            <div class="products-list">
                <?php
                $order_items = json_decode($order['order_items'], true);
                foreach ($order_items as $item):
                ?>
                <div class="product-item">
                    <div class="product-info">
                        <h3><?php echo $item['name']; ?></h3>
                        <p class="price">LKR <?php echo $item['price']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="shipping-details">
            <h2>Shipping Details</h2>
            <div class="info-grid">
                <div class="info-item">
                    <i class='bx bx-user'></i>
                    <div>
                        <h4>Full Name</h4>
                        <p><?php echo $order['customer_name']; ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class='bx bx-phone'></i>
                    <div>
                        <h4>Contact</h4>
                        <p><?php echo $order['contact_number']; ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class='bx bx-map'></i>
                    <div>
                        <h4>Shipping Address</h4>
                        <p><?php echo $order['shipping_address']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="index.php" class="btn primary">Continue Shopping</a>
            <button onclick="window.print()" class="btn secondary"><i class='bx bx-printer'></i> Print Receipt</button>
        </div>
    </div>
</body>
</html>