<?php
session_start();
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied | Sips of Serenity</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .access-denied {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .icon {
            font-size: 5rem;
            color: #ff4444;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 2rem;
        }

        p {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
            border: none;
        }

        .btn-secondary {
            background: transparent;
            color: #2c3e50;
            border: 2px solid #2c3e50;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .error-code {
            font-family: monospace;
            color: #999;
            margin-top: 2rem;
            font-size: 0.9rem;
        }

        .help-text {
            font-size: 0.9rem;
            color: #666;
            margin-top: 1rem;
        }

        .help-link {
            color: #4CAF50;
            text-decoration: none;
        }

        .help-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="access-denied">
        <i class='bx bx-shield-quarter icon'></i>
        <h1>Access Denied</h1>
        <p>You don't have permission to access this page. This area is restricted to authorized personnel only.</p>
        
        <div class="buttons">
            <a href="<?php echo $referrer; ?>" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Go Back
            </a>
            <a href="index.php" class="btn btn-primary">
                <i class='bx bx-home'></i> Home Page
            </a>
        </div>

        <p class="error-code">Error Code: 403 Forbidden</p>
        
        <p class="help-text">
            Need assistance? <a href="contact.php" class="help-link">Contact Support</a> or <a href="login.php" class="help-link">Sign In</a>
        </p>
    </div>

    <script>
        // Add smooth scroll behavior
        document.querySelectorAll('a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href.startsWith('#')) {
                    e.preventDefault();
                    document.querySelector(href).scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Optional: Add dynamic time-based greeting
        const hour = new Date().getHours();
        let greeting;
        if (hour < 12) greeting = "Good morning";
        else if (hour < 18) greeting = "Good afternoon";
        else greeting = "Good evening";
        
        document.querySelector('p').innerHTML = `${greeting}! ${document.querySelector('p').innerHTML}`;
    </script>
</body>
</html>
