<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index.html</title>
    <link rel="stylesheet" href="css/style-social-media.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <a href="index.php">
                    <img src="images/EGGHEAD(dark).png" alt="Logo">
                </a>
            </div>
            <div class="nav-links">
                <a href="index.php"><img src="images/shop.png" alt="Index"></a>
                <a href="view_cart.php"><img src="images/shopping-cart.png" alt="Cart"></a>
                <a href="user.php"><img src="images/user.png" alt="User"></a>
                <?php
                    if($loggedIn){
                        echo '<a href="user.php"><h2><span id="user-id">Profile</span></h2></a>';
                        echo '<a href="logout.php"><h2><span id="user-id">Logout</span></h2></a>';
                    } else {
                        echo '<a href="login.php"><h2><span id="user-id">Login/Signup</span></h2></a>';
                    }
                ?>
            </div>
        </div>
    </header>
</body>
</html>
