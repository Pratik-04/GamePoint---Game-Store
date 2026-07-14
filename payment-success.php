<?php
session_start();
include("db.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get parameters
$transaction_uuid = $_GET['transaction_uuid'] ?? $_SESSION['transaction_uuid'] ?? null;
$ref_id           = $_GET['ref_id'] ?? null;
$transaction_code = $_GET['transaction_code'] ?? null;

// Capture response for records
$response_data = json_encode($_GET);

if (!$transaction_uuid) {
    header("Location: cart.php");
    exit();
}

// Get user ID
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

if (!$userData) {
    die("User not found!");
}

$user_id = (int)$userData['id'];

// Get total amount
$amount_query = $conn->prepare("
    SELECT SUM(g.price) AS total 
    FROM cart c 
    JOIN games g ON c.game_id = g.id 
    WHERE c.user_id = ?
");
$amount_query->bind_param("i", $user_id);
$amount_query->execute();
$total_amount = $amount_query->get_result()->fetch_assoc()['total'] ?? 0.00;

if ($total_amount <= 0) {
    header("Location: cart.php");
    exit();
}

// Check duplicate
$check = $conn->prepare("SELECT id FROM payments WHERE transaction_uuid = ?");
$check->bind_param("s", $transaction_uuid);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    header("Location: library.php?success=1");
    exit();
}

// Insert Payment
$insert = $conn->prepare("
    INSERT INTO payments 
    (user_id, transaction_uuid, amount, status, payment_method, transaction_code, ref_id, response_data) 
    VALUES (?, ?, ?, 'COMPLETED', 'esewa', ?, ?, ?)
");
$insert->bind_param("isdsss", $user_id, $transaction_uuid, $total_amount, $transaction_code, $ref_id, $response_data);
$insert->execute();

// Add to Library
$cart_items = $conn->prepare("SELECT game_id FROM cart WHERE user_id = ?");
$cart_items->bind_param("i", $user_id);
$cart_items->execute();
$result = $cart_items->get_result();

while ($item = $result->fetch_assoc()) {
    $add = $conn->prepare("INSERT IGNORE INTO library (user_id, game_id, purchase_date) VALUES (?, ?, NOW())");
    $add->bind_param("ii", $user_id, $item['game_id']);
    $add->execute();
}

// Clear Cart
$clear = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$clear->bind_param("i", $user_id);
$clear->execute();

unset($_SESSION['transaction_uuid']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful - Game Point</title>
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
            max-width: 700px;
            margin: 0 auto;
        }
        .success {
            background: #4CAF50;
            padding: 35px 60px;
            border-radius: 15px;
            display: inline-block;
            font-size: 28px;
            margin-bottom: 30px;
        }
        button {
            padding: 14px 32px;
            margin: 10px;
            font-size: 17px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
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
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav>
        <div class="logo">🎮 Game Point</div>
        
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="games.php">Games</a>
            <a href="cart.php">Cart</a>
            <a href="library.php">Library</a>
            <a href="profile.php">Profile</a>
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin"): ?>
                <a href="admin.php">Admin</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Success Content -->
    <div class="content">
        <div class="success">✅ Payment Successful!</div>
        <p><strong>Total Paid:</strong> Rs <?php echo number_format($total_amount, 2); ?></p>
        <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transaction_uuid); ?></p>
        
        <a href="library.php"><button style="background:#00ff88;color:#000;">Go to My Library</button></a>
        <a href="history.php"><button style="background:#2196F3;color:white;">View Purchase History</button></a>
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