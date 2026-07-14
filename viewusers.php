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
<title>View Users - Admin</title>
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

<h1>👥 Registered Users</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Role</th>
        <th>Registered Date</th>
    </tr>
    <?php
    $res = $conn->query("SELECT * FROM users ORDER BY id DESC");
    while($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['username']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['phone']); ?></td>
        <td><?php echo ucfirst($row['role']); ?></td>
        <td><?php echo $row['created_at'] ?? 'N/A'; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>