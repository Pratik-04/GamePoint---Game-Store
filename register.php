<?php
include("db.php");

$errors = [];

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = trim($_POST['password']);

    // Validations
    if (empty($username) || !preg_match('/^[a-zA-Z]/', $username)) {
        $errors[] = "Username must start with a letter";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    if (empty($phone) || !preg_match('/^(97|98)\d{8}$/', $phone)) {
        $errors[] = "Phone must be 10 digits and start with 97 or 98";
    }
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users(username, email, phone, password, role) 
                                VALUES(?, ?, ?, ?, 'user')");
        $stmt->bind_param("ssss", $username, $email, $phone, $password);
        
        if ($stmt->execute()) {
            header("Location: login.php?success=1");
            exit();
        } else {
            $errors[] = "Registration failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register - Game Point</title>
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
        width:380px;
        margin:60px auto;
        padding:30px;
        border-radius:12px;
    }
    input{
        width:90%;
        padding:12px;
        margin:10px 0;
        border:none;
        border-radius:6px;
        font-size:16px;
    }
    button{
        width:90%;
        padding:12px;
        background:cyan;
        border:none;
        cursor:pointer;
        font-weight:bold;
        font-size:16px;
        margin-top:10px;
    }
    .error {
        color: #ff6b6b;
        background: rgba(255,107,107,0.1);
        padding: 10px;
        border-radius: 6px;
        margin: 10px 0;
        text-align: left;
    }
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

<h1>Register New Account</h1>

<div class="box">
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach($errors as $err): ?>
                <p>⚠️ <?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" 
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required><br>
        
        <input type="email" name="email" placeholder="Email" 
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required><br>
        
        <input type="Phone" name="phone" placeholder="Phone" maxlength="10"
               value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" 
               onkeypress="return event.charCode >= 48 && event.charCode <= 57" required><br>
   
        <input type="password" name="password" placeholder="Password" required><br>
        
        <button name="register" type="submit">Register</button>
    </form>
</div>

</body>
</html>