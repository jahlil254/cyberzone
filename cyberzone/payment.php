<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cart_items = isset($_POST['cart_items']) ? json_decode($_POST['cart_items'], true) : [];
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid cart data format');
        }

        $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
        if ($total_amount <= 0) {
            throw new Exception('Invalid total amount');
        }

        if (isset($_POST['card_number'])) {
            if (!$conn) {
                throw new Exception('Database connection failed');
            }

            $user_id = (int)$_SESSION['user_id'];
            if ($user_id <= 0) {
                throw new Exception('Invalid user ID');
            }

            $check_user = $conn->query("SELECT id FROM users WHERE id = $user_id");
            if (!$check_user || $check_user->num_rows === 0) {
                throw new Exception('User not found');
            }

            if (empty($_POST['full_name']) || empty($_POST['contact_number']) || empty($_POST['address'])) {
                throw new Exception('All shipping details are required');
            }

            if (!preg_match('/^\d{16}$/', $_POST['card_number'])) {
                throw new Exception('Invalid card number format');
            }

            $shipping_name = mysqli_real_escape_string($conn, $_POST['full_name']);
            $shipping_contact = mysqli_real_escape_string($conn, $_POST['contact_number']);
            $shipping_address = mysqli_real_escape_string($conn, $_POST['address']);
            $card_number = mysqli_real_escape_string($conn, substr($_POST['card_number'], -4));
            $cart_items_json = json_encode($cart_items);

            $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_date, order_status, customer_name, contact_number, shipping_address, card_number, order_items) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception('Prepare statement failed: ' . $conn->error);
            }

            $order_status = 'Pending';
            $stmt->bind_param("idssssss", $user_id, $total_amount, $order_status, $shipping_name, $shipping_contact, $shipping_address, $card_number, $cart_items_json);

            if (!$stmt->execute()) {
                throw new Exception('Failed to save order: ' . $stmt->error);
            }

            $order_id = $conn->insert_id;
            $_SESSION['order_success'] = true;
            header("Location: order_confirmation.php?order_id=$order_id");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        error_log('Payment Error: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberZone | Payment</title>
    <link rel="stylesheet" href="Css/payment.css">
</head>
<body>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?php 
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="payment-container">
        <h2>Order Summary</h2>
        <div class="order-details">
            <?php if (isset($cart_items) && is_array($cart_items)): ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="order-item">

                        <div class="item-info">
                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                            <span>LKR <?php echo htmlspecialchars($item['price']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="order-total">
                <strong>Total:</strong>
                <strong>LKR <?php echo isset($total_amount) ? number_format($total_amount, 2) : '0.00'; ?></strong>
            </div>
        </div>

        <form class="payment-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <h3>Shipping Details</h3>
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required pattern="[A-Za-z\s]+" title="Please enter a valid name">
            </div>
            <div class="form-group">
                <label>Contact Number</label>
                <input type="tel" name="contact_number" required pattern="[0-9]+" title="Please enter a valid phone number">
            </div>
            <div class="form-group">
                <label>Shipping Address</label>
                <textarea name="address" required minlength="10"></textarea>
            </div>

            <h3>Payment Details</h3>
            <div class="form-group">
                <label>Card Number</label>
                <input type="text" name="card_number" placeholder="1234 5678 9012 3456" required pattern="\d{16}" maxlength="16" title="Please enter a valid 16-digit card number">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="text" name="expiry" placeholder="MM/YY" required pattern="(0[1-9]|1[0-2])\/([0-9]{2})" maxlength="5" title="Please enter a valid expiry date (MM/YY)">
                </div>
                <div class="form-group">
                    <label>CVV</label>
                    <input type="text" name="cvv" placeholder="123" required pattern="\d{3}" maxlength="3" title="Please enter a valid 3-digit CVV">
                </div>
            </div>
            <input type="hidden" name="cart_items" value='<?php echo htmlspecialchars(json_encode($cart_items)); ?>'>
            <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>">
            <button type="submit" class="pay-btn">Pay Now</button>
        </form>
    </div>
</body>
</html>
