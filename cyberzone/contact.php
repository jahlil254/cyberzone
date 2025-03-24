<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if ($name && $email && $message) {
        $name = mysqli_real_escape_string($conn, $name);
        $email = mysqli_real_escape_string($conn, $email);
        $message = mysqli_real_escape_string($conn, $message);

        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, submitted_at) VALUES (?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $message);
            $stmt->execute();
            $success = true;
        } else {
            $error = "Something went wrong. Please try again later.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberZone | Contact</title>
    <link rel="stylesheet" href="Css/contact.css">
    <link rel="stylesheet" href="Css/index.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<br><br><br>

<section class="inquiry-wrapper">
    <div class="inquiry-layout">
        <div class="contact-card">
            <h2>Let’s Connect</h2>
            <p>Need help? Reach us through any method below:</p>

            <div class="info-block">
                <i class='bx bx-map'></i>
                <div>
                    <h3>Location</h3>
                    <p>CyberZone, Colombo, Sri Lanka</p>
                </div>
            </div>

            <div class="info-block">
                <i class='bx bx-phone'></i>
                <div>
                    <h3>Phone</h3>
                    <p>+94 123 456 789</p>
                    <p>+94 987 654 321</p>
                </div>
            </div>

            <div class="info-block">
                <i class='bx bx-envelope'></i>
                <div>
                    <h3>Email</h3>
                    <p>support@cyberzone.lk</p>
                    <p>help@cyberzone.lk</p>
                </div>
            </div>

            <div class="icon-links">
                <a href="#"><i class='bx bxl-facebook-circle'></i></a>
                <a href="#"><i class='bx bxl-instagram-alt'></i></a>
                <a href="#"><i class='bx bxl-twitter'></i></a>
            </div>
        </div>

        <div class="message-form-card">
            <h2>Drop a Message</h2>
            <?php if (!empty($feedback_msg)): ?>
                <div class="alert-msg"><?php echo $feedback_msg; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-cluster">
                <input type="text" id="name" name="name" required>
                    <label>Name</label>
                </div>

                <div class="input-cluster">
                <input type="email" id="email" name="email" required>
                    <label>Email Address</label>
                </div>

                <div class="input-cluster">
                    <textarea id="message" name="message" rows="5" required></textarea>
                    <label>Your Message</label>
                </div>

                <button type="submit" class="submit-action-btn">Send Now</button>
            </form>
        </div>
    </div>

    <div class="location-mapbox">
        <iframe src="https://www.google.com/maps/embed?...your-map-link..." width="100%" height="450" style="border:0;" allowfullscreen loading="lazy"></iframe>
    </div>
</section>
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
    const logoMain = document.getElementById('logoMain');
    const logoDefault = 'Assets/logo1.png';
    const logoScrolled = 'Assets/logo2.png';

    window.addEventListener('scroll', () => {
        logoMain.src = window.scrollY > 50 ? logoScrolled : logoDefault;
    });

    window.addEventListener('scroll', () => {
        const headerNav = document.querySelector('.navbar');
        headerNav.classList.toggle('scrolled', window.scrollY > 50);
    });
</script>
</body>
</html>
