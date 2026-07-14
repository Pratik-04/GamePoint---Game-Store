<?php
session_start();
include("db.php");

if(!isset($_SESSION['user'])){
  
}

$user_id = null;

// Get actual user ID from database 
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gamepoint - My college multiplayer social gaming project. Play free online games, explore features, and see the development journey of this student-built platform.">
    <meta name="keywords" content="Gamepoint, college game project, multiplayer gaming, student game development, online social games">
    <meta name="author" content="Your Full Name">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Gamepoint - College Gaming Project">
    <meta property="og:description" content="Interactive multiplayer gaming platform built as a college project.">
    <meta property="og:image" content="https://yourdomain.com/images/gamepoint-screenshot.jpg">
    <meta property="og:url" content="https://yourdomain.com">
    <meta property="og:type" content="website">
    <title>Gamepoint - College Multiplayer Gaming Project | Your Name</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="favicon.ico">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CreativeWork",
      "name": "Gamepoint",
      "description": "Multiplayer social gaming platform developed as a college project",
      "author": {
        "@type": "Person",
        "name": "Pratik Shrestha"
      },
      "datePublished": "2026",
      "genre": "Game",
      "url": "https://gamepoint.com"
    }
</script>
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

.hero {
    margin-top: 100px;
    margin-bottom: 80px;
}

.popular-grid {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 80px;
}

.card {
    background:#1e1e2f;
    width: 220px;
    border-radius: 12px;
    padding: 10px;
    transition: 0.3s;
}

.card:hover {
    transform: scale(1.05);
}

.card img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    border-radius: 8px;
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

.footer-stats {
    background: #1e1e2f;
    padding: 40px 20px;
    margin-top: 80px;
}

.stats-grid {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 50px;
    max-width: 1000px;
    margin: 0 auto;
}

.stat-item h2 {
    color: cyan;
    font-size: 36px;
    margin: 10px 0 5px 0;
}

.stat-item p {
    color: #ccc;
    margin: 0;
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

<div class="hero">
    <h1>🎮 Welcome to Game Point</h1>
    <p>Your favorite gaming store</p>
    <a href="games.php"><button style="padding:12px 30px; background:cyan; color:#141e30; border:none; border-radius:6px; font-size:16px; cursor:pointer;">Explore All Games</button></a>
</div>

<h2 style="margin:50px 0 30px;">🔥 Popular Games</h2>
<div class="popular-grid">
<?php
$res = $conn->query("SELECT * FROM games ORDER BY RAND() LIMIT 4");

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
        <p>Rs <?php echo $g['price']; ?></p>
        
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
<?php endwhile; ?>
</div>

<div class="footer-stats">
    <h2>Platform Statistics</h2>
    <div class="stats-grid">
        <?php
        $total_games = $conn->query("SELECT COUNT(*) as count FROM games")->fetch_assoc()['count'] ?? 0;
        $total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'] ?? 0;
        $total_purchases = $conn->query("SELECT COUNT(*) as count FROM library")->fetch_assoc()['count'] ?? 0;
        ?>
        <div class="stat-item">
            <h2><?php echo $total_games; ?></h2>
            <p>Total Games</p>
        </div>
        <div class="stat-item">
            <h2><?php echo $total_users; ?></h2>
            <p>Registered Users</p>
        </div>
        <div class="stat-item">
            <h2><?php echo $total_purchases; ?></h2>
            <p>Games Purchased</p>
        </div>
    </div>
</div>

</body>
</html>