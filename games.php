<?php
session_start();
include("db.php");

$user_id = null;

if(isset($_SESSION['user'])) {
    $username = $conn->real_escape_string($_SESSION['user']);
    $u = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if($u && $u->num_rows > 0) {
        $user_id = $u->fetch_assoc()['id'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Games - Game Point</title>

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

.category-filters {
    margin: 30px 0;
}

.category-filters a {
    display: inline-block;
    background: #1e1e2f;
    color: cyan;
    padding: 10px 18px;
    margin: 5px;
    border-radius: 30px;
    text-decoration: none;
}

.category-filters a:hover, .category-filters a.active {
    background: cyan;
    color: #141e30;
}

.card{
    background:#1e1e2f;
    display:inline-block;
    margin:15px;
    padding:15px;
    width:230px;
    border-radius:12px;
    transition: 0.3s;
}

.card:hover {
    transform: scale(1.05);
}

.card img{
    width:100%;
    height:140px;
    object-fit:cover;
    border-radius:8px;
}

button{
    background:cyan;
    color:#141e30;
    border:none;
    padding:10px 15px;
    margin-top:10px;
    cursor:pointer;
    border-radius:5px;
    font-weight:bold;
}

.library-btn {
    background:#28a745 !important;
    color:white !important;
}

h1 {
    margin: 40px 0 20px 0;
}
</style>
</head>

<body>

<nav>
    <div style="font-size:24px; font-weight:bold; color:cyan;">🎮 Game Point</div>
    
    <div>
        <a href="index.php">Home</a>
        <a href="games.php">Games</a>
        
        <?php if(!isset($_SESSION['user'])): ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php else: ?>
            <a href="cart.php">Cart</a>
            <a href="library.php">Library</a>
            <a href="profile.php">Profile</a>
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin"): ?>
                <a href="admin.php">Admin</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</nav>

<h1>🎮 All Games</h1>

<!-- Category Filters -->
<div class="category-filters">
    <a href="games.php" <?php if(!isset($_GET['cat'])) echo 'class="active"'; ?>>All</a>
    <a href="games.php?cat=Action" <?php if(isset($_GET['cat']) && $_GET['cat']=='Action') echo 'class="active"'; ?>>Action</a>
    <a href="games.php?cat=RPG" <?php if(isset($_GET['cat']) && $_GET['cat']=='RPG') echo 'class="active"'; ?>>RPG</a>
    <a href="games.php?cat=Shooter" <?php if(isset($_GET['cat']) && $_GET['cat']=='Shooter') echo 'class="active"'; ?>>Shooter</a>
    <a href="games.php?cat=Adventure" <?php if(isset($_GET['cat']) && $_GET['cat']=='Adventure') echo 'class="active"'; ?>>Adventure</a>
    <a href="games.php?cat=Sports" <?php if(isset($_GET['cat']) && $_GET['cat']=='Sports') echo 'class="active"'; ?>>Sports</a>
</div>

<?php
$category = isset($_GET['cat']) ? $conn->real_escape_string($_GET['cat']) : '';

if($category != '') {
    $sql = "SELECT * FROM games WHERE category = '$category'";
} else {
    $sql = "SELECT * FROM games";
}

$res = $conn->query($sql);

if($res->num_rows > 0) {
    while($g = $res->fetch_assoc()):
        $game_id = (int)$g['id'];
        $in_library = false;

        if($user_id !== null) {
            $check = $conn->query("SELECT 1 FROM library WHERE user_id = $user_id AND game_id = $game_id LIMIT 1");
            if($check && $check->num_rows > 0) {
                $in_library = true;
            }
        }
?>
    <div class="card">
        <img src="uploads/<?php echo htmlspecialchars($g['image']); ?>" alt="<?php echo htmlspecialchars($g['name']); ?>">
        <h3><?php echo htmlspecialchars($g['name']); ?></h3>
        <p>Rs <?php echo $g['price']; ?> | <?php echo htmlspecialchars($g['category']); ?></p>
        
        <?php if($user_id !== null): ?>
            <?php if($in_library): ?>
                <a href="library.php">
                    <button class="library-btn">✓ In Library</button>
                </a>
            <?php else: ?>
                <a href="cart.php?buy=<?php echo $g['id']; ?>">
                    <button>Add to Cart</button>
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php 
    endwhile;
} else {
    echo "<p style='margin-top:50px; font-size:18px;'>No games found in this category.</p>";
}
?>

</body>
</html>