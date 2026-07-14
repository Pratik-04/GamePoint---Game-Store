<?php
session_start();
include("db.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
    exit();
}

$username = $_SESSION['user'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile - Game Point</title>

<style>
body{
    font-family:Arial;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
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

.box{
    background:#1e1e2f;
    width:420px;
    margin:80px auto;
    padding:40px;
    border-radius:12px;
    box-shadow:0 0 15px cyan;
}

a.btn {
    display: inline-block;
    background: cyan;
    color: #141e30;
    padding: 12px 25px;
    margin: 10px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
}

.logout-btn {
    background: red;
    color: white;
}
</style>
</head>

<body>

<!-- Your Preferred Navbar -->
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

<div class="box">
    <h1>👤 My Profile</h1>
    
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?></h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
    <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>

    <br>

    <?php if($user['role'] == "admin"): ?>
        <a href="admin.php" class="btn">🛠 Go to Admin Panel</a>
    <?php endif; ?>

    <a href="?logout=true" class="btn logout-btn">Logout</a>
</div>

</body>
</html>