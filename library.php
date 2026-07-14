<?php
session_start();
include("db.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// Get user id
$user = $_SESSION['user'];
$u = $conn->query("SELECT id FROM users WHERE username='$user'");
$uid = $u->fetch_assoc()['id'];

// Simulate Payment
if(isset($_GET['pay']) && $_GET['pay'] == 'all'){
    $cart_items = $conn->query("SELECT game_id FROM cart WHERE user_id = '$uid'");
    
    while($item = $cart_items->fetch_assoc()){
        $gid = $item['game_id'];
        $conn->query("INSERT IGNORE INTO library (user_id, game_id) VALUES ('$uid', '$gid')");
    }
    
    $conn->query("DELETE FROM cart WHERE user_id = '$uid'");
    
    header("Location: library.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Library - Game Point</title>

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

.library-container {
    max-width: 1000px;
    margin: 40px auto;
}

.card {
    background:#1e1e2f;
    display:inline-block;
    margin:15px;
    padding:15px;
    width:230px;
    border-radius:12px;
}

.card img{
    width:100%;
    height:140px;
    object-fit:cover;
    border-radius:8px;
}

.success {
    background: #4CAF50;
    padding: 15px;
    border-radius: 8px;
    margin: 20px auto;
    max-width: 600px;
}

button{
    padding:10px;
    border:none;
    border-radius:8px;
    background:cyan;
    cursor:pointer;
    font-weight:bold;
}
</style>
</head>

<body>

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

<h1>🎮 My Library</h1>

<?php if(isset($_GET['success'])): ?>
    <p class="success">✅ Payment Successful! Games added.</p>
<?php endif; ?>

<div class="library-container">

<?php
$sql = "SELECT g.* FROM library l 
        JOIN games g ON l.game_id = g.id 
        WHERE l.user_id = '$uid'";

$res = $conn->query($sql);

if($res->num_rows > 0){
    while($g = $res->fetch_assoc()):

        $name = strtolower(trim($g['name']));

        // GTA V detection
        $isGTAV = (
            (strpos($name, "gta") !== false || strpos($name, "grand theft auto") !== false)
            &&
            (strpos($name, "v") !== false || strpos($name, "5") !== false)
        );

        // Among Us detection
        $isAmongUs = (strpos($name, "among") !== false);
?>

    <div class="card">
        <img src="uploads/<?php echo htmlspecialchars($g['image']); ?>">
        <h3><?php echo htmlspecialchars($g['name']); ?></h3>
        <p>Category: <?php echo htmlspecialchars($g['category'] ?? 'N/A'); ?></p>

        <?php if($isGTAV): ?>
            <a href="steam://run/271590">
                <button>▶ Play Now</button>
            </a>

        <?php elseif($isAmongUs): ?>
            <a href="steam://run/945360">
                <button>▶ Play Now</button>
            </a>

        <?php else: ?>
            <button onclick="alert('Installing <?php echo addslashes($g['name']); ?>... 🎮')">
                Install Now
            </button>
        <?php endif; ?>

    </div>

<?php 
    endwhile;
} else {
    echo "<h3>Your Library is empty 😕</h3>";
}
?>

</div>

</body>
</html>