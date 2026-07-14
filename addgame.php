<?php
session_start();
include("db.php");

if(!isset($_SESSION['user']) || $_SESSION['role'] != "admin"){
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['add'])){
    $name     = trim($_POST['name']);
    $price    = (int)$_POST['price'];
    $category = trim($_POST['category']);

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $img_name = time() . "_" . basename($_FILES['image']['name']);
        $target   = "uploads/" . $img_name;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
            $sql = "INSERT INTO games (name, price, image, category) 
                    VALUES ('$name', $price, '$img_name', '$category')";
            
            if($conn->query($sql)){
                $message = "✅ Game added successfully!";
            } else {
                $message = "❌ Database Error!";
            }
        } else {
            $message = "❌ Failed to upload image!";
        }
    } else {
        $message = "❌ Please select an image!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Game - Admin</title>

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
    width:450px;
    margin:60px auto;
    padding:35px;
    border-radius:12px;
}

input, select, button{
    width:90%;
    padding:12px;
    margin:10px 0;
    border:none;
    border-radius:6px;
}

button{
    background:cyan;
    color:#141e30;
    font-weight:bold;
    cursor:pointer;
}

.message { padding:12px; margin:15px 0; border-radius:6px; }
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
            <a href="admin.php">Admin</a>
        <?php endif; ?>
    </div>
</nav>

<h1>🛠 Add New Game</h1>

<div class="box">
    <?php if($message): ?>
        <p class="message" style="background:<?php echo strpos($message,'✅')!==false ? '#4CAF50' : '#f44336'; ?>;">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Game Name" required><br>
        <input type="number" name="price" placeholder="Price (Rs)" min="0" required><br>
        
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="Action">Action</option>
            <option value="RPG">RPG</option>
            <option value="Shooter">Shooter</option>
            <option value="Adventure">Adventure</option>
            <option value="Sports">Sports</option>
            <option value="Racing">Racing</option>
            <option value="Fighting">Fighting</option>
            <option value="Strategy">Strategy</option>
        </select><br>
        
        <input type="file" name="image" accept="image/*" required><br><br>
        <button type="submit" name="add">Add Game</button>
    </form>
</div>

</body>
</html>