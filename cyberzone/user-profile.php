<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($username && $email) {
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);

        $conn->query("UPDATE users SET username = '$username', email = '$email' WHERE id = $user_id");

        if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
            $result = $conn->query("SELECT password FROM users WHERE id = $user_id");
            $user = $result->fetch_assoc();

            if (password_verify($current_password, $user['password'])) {
                if ($new_password === $confirm_password) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $conn->query("UPDATE users SET password = '$hashed_password' WHERE id = $user_id");
                    $_SESSION['success'] = "Profile and password updated successfully.";
                } else {
                    $_SESSION['error'] = "New passwords do not match.";
                }
            } else {
                $_SESSION['error'] = "Current password is incorrect.";
            }
        } else {
            $_SESSION['success'] = "Profile updated successfully.";
        }

        header("Location: user-profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Username and email are required.";
        header("Location: user-profile.php");
        exit();
    }
}

// Fetch user details
$result = $conn->query("SELECT username, email FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/user-profile.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="profile-container">
        <div class="sidebar">
            <div class="user-info">
                <i class='bx bxs-user-circle'></i>
                <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <div class="profile-nav">
                <a href="#" class="active"><i class='bx bx-user'></i> Profile</a>
                <a href="index.php"><i class='bx bx-log-out'></i> Continue Shopping</a>
            </div>
        </div>

        <div class="main-content">
            <div class="tab-content active">
                <h2>Update Profile</h2>

                <?php if ($success): ?>
                    <div class="success-box"><?php echo htmlspecialchars($success); ?></div>
                <?php elseif ($error): ?>
                    <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" class="profile-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <h3>Change Password</h3>
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>

                    <button type="submit" class="update-btn"><i class='bx bx-save'></i> Update Profile</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide alert box after 3 seconds
        const successBox = document.querySelector('.success-box');
        const errorBox = document.querySelector('.error-box');

        if (successBox) {
            setTimeout(() => {
                successBox.style.display = 'none';
            }, 3000);
        }

        if (errorBox) {
            setTimeout(() => {
                errorBox.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>
