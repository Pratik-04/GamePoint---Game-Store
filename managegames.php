<?php
session_start();
include("db.php");

if(!isset($_SESSION['user']) || $_SESSION['role'] != "admin"){
    header("Location: login.php");
    exit();
}

// Delete game
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM games WHERE id = $id");
    header("Location: managegames.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Games - Admin</title>
<style>
body {font-family:Arial; background:linear-gradient(135deg,#0f2027,#203a43,#2c5364); color:white; margin:0;}
nav {background:#1e1e2f; padding:15px; display:flex; justify-content:space-between; align-items:center;}
nav a {color:cyan; margin:0 12px; text-decoration:none; font-weight:bold;}
table {width:90%; margin:30px auto; border-collapse:collapse;}
th, td {padding:12px; border:1px solid #444; text-align:left;}
th {background:#1e1e2f;}
.delete-btn {background:red; color:white; padding:6px 12px; text-decoration:none; border-radius:5px;}
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

<h1>📋 Manage Games</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Category</th>
        <th>Action</th>
    </tr>
    <?php
    $res = $conn->query("SELECT * FROM games ORDER BY id DESC");
    while($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><img src="uploads/<?php echo $row['image']; ?>" width="60" height="60" style="object-fit:cover;"></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td>Rs <?php echo $row['price']; ?></td>
        <td><?php echo $row['category']; ?></td>
        <td>
            <a href="managegames.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this game?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>