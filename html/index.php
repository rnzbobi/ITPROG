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
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <img src="images/download.png" alt="Logo">
            </div>
            <form class="search-form">
                <input type="text" class="search-input" placeholder="Search...">
                <button type="submit" class="search-button"><img src="images/search-interface-symbol.png" alt="Search"></button>
            </form>
            <div class="nav-links">
                <a href="user.html"><img src="images/user.png" alt="User"></a>
                <a href="cart.html"><img src="images/shopping-cart.png" alt="Cart"></a>
                <?php
            if($loggedIn){
                echo '<a href="profile.php"><h2><span id="user-id">Profile</span></h2></a>';
                echo '<a href="logout.php"><h2><span id="user-id">Logout</span></h2></a>';
            } else {
                echo '<a href="login.php"><h2><span id="user-id">Login/Signup</span></h2></a>';
            }
            ?>

            </div>
            <div class="Balance-header">
                <h2 id="balance">Balance: <span id="balance-value">10000</span></h2>
            </div>
        </div>
        
    </header>    
    <main>
    
    </main>
</body>
</html>
