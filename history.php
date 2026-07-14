<?php
session_start();
include("db.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$username = $conn->real_escape_string($_SESSION['user']);
$u = $conn->query("SELECT id FROM users WHERE username = '$username'");
$uid = $u->fetch_assoc()['id'] ?? 0;

if($uid == 0){
    echo "User not found!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Purchase History - Game Point</title>

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

.container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
}

h1 {
    margin: 30px 0;
}

.table-container {
    background: #1e1e2f;
    border-radius: 12px;
    overflow: hidden;
    margin: 20px auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 15px;
    text-align: left;
}

th {
    background: #243b55;
    color: cyan;
}

tr:nth-child(even) {
    background: #2a2a40;
}

tr:hover {
    background: #33334d;
}

.game-img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
}

.empty {
    margin: 80px 0;
    font-size: 20px;
    color: #aaa;
}

.btn {
    background: cyan;
    color: #141e30;
    border: none;
    padding: 12px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: none;
    display: inline-block;
    margin: 10px;
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
        <a href="history.php">History</a>
        <a href="profile.php">Profile</a>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin"): ?>
            <a href="admin.php">Admin</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">
    <h1>🛒 Purchase History</h1>
    
    <?php
    $sql = "SELECT g.name, g.image, g.price, g.category, l.id as library_id 
            FROM library l 
            JOIN games g ON l.game_id = g.id 
            WHERE l.user_id = $uid 
            ORDER BY l.id DESC";     // Using id as fallback for ordering

    $res = $conn->query($sql);
    ?>

    <?php if($res && $res->num_rows > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Game</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Purchased On</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td>
                            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" 
                                 class="game-img" alt="">
                        </td>
                        <td><?php echo htmlspecialchars($row['category'] ?? 'N/A'); ?></td>
                        <td><strong>Rs <?php echo number_format($row['price']); ?></strong></td>
                        <td>Just now / Recent</td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <a href="library.php" class="btn">Go to My Library</a>
        
    <?php else: ?>
        <div class="empty">
            <h2>You haven't purchased any games yet 😕</h2>
            <p>Start exploring and buying games from the store.</p>
            <br>
            <a href="games.php" class="btn">Browse Games</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>