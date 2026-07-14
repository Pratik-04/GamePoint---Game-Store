<?php
session_start();
include("db.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// Get logged in user ID
$user = $_SESSION['user'];
$u = $conn->query("SELECT id FROM users WHERE username='$user'");
$uid = $u->fetch_assoc()['id'];

// Add game to cart
if(isset($_GET['buy'])){
    $gid = (int)$_GET['buy'];
    
    // Check if already in cart
    $check = $conn->query("SELECT * FROM cart WHERE user_id='$uid' AND game_id='$gid'");
    
    if($check->num_rows == 0){
        $conn->query("INSERT INTO cart(user_id, game_id) VALUES('$uid', '$gid')");
    }
    
    header("Location: cart.php");
    exit();
}

// Remove game from cart
if(isset($_GET['remove'])){
    $cid = (int)$_GET['remove'];
    $conn->query("DELETE FROM cart WHERE id='$cid' AND user_id='$uid'");
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Cart - Game Point</title>

<style>
body{
    font-family:Arial;
    background:linear-gradient(135deg,#141e30,#243b55);
    color:white;
    margin:0;
    text-align:center;
}

nav{
    background:#1e1e2f;
    padding:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

nav a{
    color:cyan;
    margin:0 12px;
    text-decoration:none;
    font-weight:bold;
}

.cart-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 20px;
}

.card {
    background:#1e1e2f;
    display:flex;
    margin:15px auto;
    padding:15px;
    width:85%;
    max-width:700px;
    border-radius:12px;
    align-items:center;
    box-shadow: 0 0 10px rgba(0,255,255,0.1);
}

.card img{
    width:130px;
    height:130px;
    object-fit:cover;
    border-radius:8px;
    margin-right:20px;
}

.info {
    flex:1;
    text-align:left;
}

.info h3 {
    margin: 5px 0;
}

.total {
    font-size: 26px;
    margin: 40px 0 20px;
    color: cyan;
}

button {
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}

.remove-btn {
    background: #ff4444;
    color: white;
}

.pay-btn {
    background: #4CAF50;
    color: white;
    padding: 15px 40px;
    font-size: 18px;
    margin-top: 20px;
}
</style>
</head>

<body>

<!-- Navbar -->
<nav>
    <div style="font-size:24px; font-weight:bold; color:cyan;">🎮 Game Point</div>
    
    <div>
        <a href="index.php">Home</a>
        <a href="games.php">Games</a>
        <a href="cart.php">Cart</a>
        <a href="library.php">Library</a>
        <a href="profile.php">Profile</a>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin"): ?>
            <a href="addgame.php">Add Game</a>
        <?php endif; ?>
    </div>
</nav>

<h1>🛒 Your Shopping Cart</h1>

<div class="cart-container">

<?php
$total = 0;

$sql = "SELECT c.id as cart_id, g.id, g.name, g.price, g.image, g.category 
        FROM cart c 
        JOIN games g ON c.game_id = g.id 
        WHERE c.user_id = '$uid' 
        ORDER BY c.id DESC";

$res = $conn->query($sql);

if($res->num_rows > 0){
    while($row = $res->fetch_assoc()){
        $total += $row['price'];
?>
    <div class="card">
        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" 
             alt="<?php echo htmlspecialchars($row['name']); ?>">
        
        <div class="info">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p>Category: <?php echo htmlspecialchars($row['category'] ?? 'N/A'); ?></p>
            <p><strong>Rs <?php echo number_format($row['price']); ?></strong></p>
        </div>
        
        <a href="cart.php?remove=<?php echo $row['cart_id']; ?>">
            <button class="remove-btn">Remove</button>
        </a>
    </div>
<?php 
    }
?>

    <div class="total">
        Total Amount: <strong>Rs <?php echo number_format($total); ?></strong>
    </div>

    <a href="payment.php">
        <button class="pay-btn">💳 Pay with eSewa</button>
    </a>

<?php 
} else {
    echo "<h3>Your cart is empty 😕</h3>";
    echo '<p>Browse and add some games to your cart.</p>';
    echo '<a href="games.php"><button style="padding:12px 30px; background:cyan; color:#141e30;">Browse Games</button></a>';
}
?>

</div>

</body>
</html>