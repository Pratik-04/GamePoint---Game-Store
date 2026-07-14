<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment Failed - Game Point</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #141e30, #243b55);
            color: white;
            line-height: 1.6;
        }

        /* ================== NAVIGATION ================== */
        nav {
            background: #0f1624;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #1e2a44;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: cyan;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            font-weight: bold;
            transition: 0.3s;
        }
        .nav-links a:hover {
            color: cyan;
        }

        /* ================== MAIN CONTENT ================== */
        .content {
            text-align: center;
            padding: 120px 20px 100px;
            max-width: 600px;
            margin: 0 auto;
        }
        .content h1 {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .content p {
            font-size: 19px;
            opacity: 0.9;
            margin-bottom: 40px;
        }
        button {
            padding: 14px 32px;
            font-size: 17px;
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #ff6666;
            transform: translateY(-3px);
        }

        /* ================== FOOTER ================== */
        footer {
            background: #0a0f1c;
            padding: 40px 5% 25px;
            text-align: center;
            border-top: 1px solid #1e2a44;
        }
        .footer-links a {
            color: #aaa;
            text-decoration: none;
            margin: 0 15px;
            font-size: 15px;
        }
        .footer-links a:hover {
            color: cyan;
        }
        .copyright {
            margin-top: 25px;
            opacity: 0.6;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav>
        <div class="logo">🎮 Game Point</div>
        
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="games.php">Games</a>
            
            <?php if(!isset($_SESSION['user'])): ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php else: ?>
                <a href="cart.php">Cart</a>
                <a href="library.php">Library</a>
                <a href="profile.php">Profile</a>
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin"): ?>
                    <a href="admin.php">Admin</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Payment Failed Content -->
    <div class="content">
        <h1>❌ Payment Failed or Cancelled</h1>
        <p>Something went wrong with your transaction.<br>Please try again or contact support if the issue persists.</p>
        <a href="cart.php"><button>← Back to Cart</button></a>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-links">
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
            <a href="privacy.php">Privacy Policy</a>
            <a href="terms.php">Terms of Service</a>
        </div>
        <div class="copyright">
            &copy; <?php echo date("Y"); ?> Game Point. All Rights Reserved.
        </div>
    </footer>

</body>
</html>