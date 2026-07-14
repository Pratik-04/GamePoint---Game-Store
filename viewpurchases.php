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
<title>View Purchases - Admin</title>
<style>
body {
    font-family:Arial;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:white;
    margin:0;
}
nav {
    background:#1e1e2f;
    padding:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
nav a {
    color:cyan;
    margin:0 12px;
    text-decoration:none;
    font-weight:bold;
}
table {
    width:90%;
    margin:30px auto;
    border-collapse:collapse;
    background:#1e1e2f;
}
th, td {
    padding:12px;
    border:1px solid #444;
    text-align:left;
}
th {
    background:#141e30;
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

<h1>📦 All Purchases (Library)</h1>

<table>
    <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Game Name</th>
        <th>Price</th>
        <th>Category</th>
        <th>Purchased On</th>
    </tr>
    <?php
    $sql = "SELECT l.user_id, u.username, g.name, g.price, g.category, l.purchased_at 
            FROM library l 
            JOIN users u ON l.user_id = u.id 
            JOIN games g ON l.game_id = g.id 
            ORDER BY l.purchased_at DESC";

    $res = $conn->query($sql);
    while($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $row['user_id']; ?></td>
        <td><?php echo htmlspecialchars($row['username']); ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td>Rs <?php echo $row['price']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td><?php echo $row['purchased_at']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>