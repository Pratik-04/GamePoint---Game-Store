<?php
session_start();
include("db.php");

$error = "";

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if($result && $result->num_rows > 0){
        $user = $result->fetch_assoc();

        if($user['password'] == $password){
            $_SESSION['user'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit();
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - Game Point</title>

<style>
body{
    font-family:Arial;
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:white;
    text-align:center;
    margin:0;
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
    width:350px;
    margin:80px auto;
    padding:30px;
    border-radius:12px;
}

input{
    width:90%;
    padding:12px;
    margin:10px 0;
    border:none;
    border-radius:6px;
}

button{
    width:90%;
    padding:12px;
    background:cyan;
    border:none;
    cursor:pointer;
    font-weight:bold;
    margin-top:10px;
}

.error { color: red; margin: 15px 0; }
</style>
</head>

<body>

<nav>
    <div style="font-size:24px; font-weight:bold; color:cyan;">🎮 Game Point</div>
    
    <div>
        <a href="index.php">Home</a>
        <a href="games.php">Games</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>
</nav>

<h1>Login to Game Point</h1>

<div class="box">
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
    
    <?php if($error != "") echo "<p class='error'>$error</p>"; ?>
    
    <br>
    <a href="register.php" style="color:cyan;">Create New Account</a>
</div>

</body>
</html>