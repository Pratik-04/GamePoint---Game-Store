<?php
session_start();
include("db.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get user ID safely
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

if (!$userData) {
    die("User not found!");
}

$user_id = $userData['id'];

// Get total amount
$total_query = $conn->prepare("
    SELECT SUM(g.price) as total 
    FROM cart c 
    JOIN games g ON c.game_id = g.id 
    WHERE c.user_id = ?
");
$total_query->bind_param("i", $user_id);
$total_query->execute();
$total_row = $total_query->get_result()->fetch_assoc();
$amount = $total_row['total'] ?? 0;

if ($amount <= 0) {
    header("Location: cart.php");
    exit();
}

// eSewa Configuration
$product_code = "EPAYTEST";                    
$secret_key   = "8gBm/:&EnhH.1/q";             
$success_url  = "http://localhost/GamePoint/payment-success.php";   
$failure_url  = "http://localhost/GamePoint/payment-failure.php";   

// Generate unique transaction UUID
$transaction_uuid = "GAMEPOINT-" . $user_id . "-" . time();

// Store in session for backup
$_SESSION['transaction_uuid'] = $transaction_uuid;

// Prepare signature
$message = "total_amount={$amount},transaction_uuid={$transaction_uuid},product_code={$product_code}";
$signature = base64_encode(hash_hmac('sha256', $message, $secret_key, true));
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment - eSewa | Game Point</title>
<style>
    body{font-family:Arial;background:linear-gradient(135deg,#141e30,#243b55);color:white;margin:0;text-align:center;}
    nav{background:#1e1e2f;padding:15px;display:flex;justify-content:space-between;align-items:center;}
    .box{background:#1e1e2f;width:500px;margin:80px auto;padding:40px;border-radius:12px;}
    button{padding:15px 40px;background:#4CAF50;color:white;border:none;border-radius:8px;font-size:18px;cursor:pointer;margin-top:20px;}
</style>
</head>
<body>

<nav>
    <div style="font-size:24px;font-weight:bold;color:cyan;">🎮 Game Point</div>
    <div>
        <a href="index.php">Home</a>
        <a href="games.php">Games</a>
        <a href="cart.php">Cart</a>
        <a href="library.php">Library</a>
    </div>
</nav>

<div class="box">
    <h2>Complete Payment with eSewa</h2>
    <h3>Total Amount: Rs <?php echo number_format($amount, 2); ?></h3>
    <p>You will be redirected to eSewa Sandbox.</p>

    <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
        <input type="hidden" name="amount" value="<?php echo $amount; ?>">
        <input type="hidden" name="tax_amount" value="0">
        <input type="hidden" name="total_amount" value="<?php echo $amount; ?>">
        <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
        <input type="hidden" name="product_code" value="<?php echo $product_code; ?>">
        <input type="hidden" name="product_service_charge" value="0">
        <input type="hidden" name="product_delivery_charge" value="0">
        <input type="hidden" name="success_url" value="<?php echo $success_url; ?>">
        <input type="hidden" name="failure_url" value="<?php echo $failure_url; ?>">
        <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
        <input type="hidden" name="signature" value="<?php echo $signature; ?>">

        <button type="submit">Pay with eSewa</button>
    </form>
</div>

</body>
</html>