<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username or email already exists
    $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or email already exists!');</script>";
    } else {
        // Insert new user
        $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("sss", $username, $email, $password);

        if ($insert_stmt->execute()) {
            $_SESSION['username'] = $username;
            echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Registration failed!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CyberZone</title>
    <link rel="stylesheet" href="Css/register.css?v=<?php echo time(); ?>">

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
    <form id="register-form" method="post" action="register.php">
        <h1>Register</h1>
        
        <div class="input-row">
            <div class="input-box">
                <input type="text" id="reg-username" name="username" placeholder="Username" required>
                <i class="bx bxs-user"></i>
            </div>
            <div class="input-box">
                <input type="email" id="reg-email" name="email" placeholder="Email" required>
                <i class="bx bxs-envelope"></i>
            </div>
        </div>

        <div class="input-row">
            <div class="input-box">
                <input type="password" id="reg-password" name="password" placeholder="Password" required>
                <i class="bx bxs-lock-alt"></i>
            </div>
            <div class="input-box">
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" required>
                <i class="bx bxs-lock-alt"></i>
            </div>
        </div>

        <button type="submit" class="btn">Register</button>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </form>
    </div>
</body>
</html>