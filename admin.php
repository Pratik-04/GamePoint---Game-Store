<?php
session_start();
include("db.php");

if(!isset($_SESSION['user']) || $_SESSION['role'] != "admin"){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard - Game Point</title>

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

.dashboard {
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
}

.welcome {
    margin-bottom: 50px;
    font-size: 32px;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    padding: 0 20px;
}

.admin-card {
    background: #1e1e2f;
    border-radius: 12px;
    padding: 35px 25px;
    transition: 0.3s;
    box-shadow: 0 0 15px rgba(0,255,255,0.1);
}

.admin-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 0 25px rgba(0,255,255,0.3);
}

.admin-card h3 {
    color: cyan;
    margin: 10px 0 18px 0;
    font-size: 23px;
}

.admin-card p {
    color: #ccc;
    margin-bottom: 25px;
    line-height: 1.6;
}

.btn {
    display: inline-block;
    background: cyan;
    color: #141e30;
    padding: 14px 32px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s;
}

.btn:hover {
    background: white;
    transform: scale(1.05);
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
        <a href="admin.php">Admin</a>
    </div>
</nav>

<div class="dashboard">

    <div class="welcome">
        <h1>Welcome back, <strong>GamePoint</strong></h1>
    </div>

    <div class="card-grid">

        <!-- Add New Game -->
        <div class="admin-card">
            <h3>➕ Add New Game</h3>
            <p>Add new games to the store with image and category</p>
            <a href="addgame.php" class="btn">Go to Add Game</a>
        </div>

        <!-- Manage Games -->
        <div class="admin-card">
            <h3>📋 Manage Games</h3>
            <p>View, edit or delete existing games</p>
            <a href="managegames.php" class="btn">Manage Games</a>
        </div>

        <!-- View Users -->
        <div class="admin-card">
            <h3>👥 View Users</h3>
            <p>See all registered users and their details</p>
            <a href="viewusers.php" class="btn">View Users</a>
        </div>

        <!-- View Purchases -->
        <div class="admin-card">
            <h3>📦 View Purchases</h3>
            <p>See all games purchased by users</p>
            <a href="viewpurchases.php" class="btn">View Purchases</a>
        </div>

    
    </div>

</div>

</body>
</html>