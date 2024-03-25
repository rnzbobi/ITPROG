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
    <link rel="stylesheet" href="css/social_style.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <aside class="sidebar">
            <div class="logo">
                <a href="index.php">
                    <img src="images/EGGHEAD(dark).png" alt="Logo">
                </a>
            </div>
            <div class="nav-links">
                <a href="index.php"><img src="images/shop.png" alt="Index"></a>
                <a href="view_cart.php"><img src="images/shopping-cart.png" alt="Cart"></a>
                <a href="user.php"><img src="images/user.png" alt="User"></a>
            </div>
            <div class="word-links">
            <?php
                    if($loggedIn){
                        echo '<button id="post-button"><span id="post-id">Post</span></button><br><br><br><br><br>';
                        echo '<a href="user.php"><span id="user-id">Profile</span></a><br><br><br><br>';
                        echo '<a href="logout.php"><span id="user-id">Logout</span></a><br><br><br>';
                    } else {
                        echo '<a href="login.php"><span id="user-id">Login/Signup</span></a><br><br><br>';
                    }
            ?>
            </div>
    </aside>
    <main>
        <?php include 'display_posts.php'; ?>
    </main>
    <div class="clearfix"></div>
    <script>
        // Add event listener to the post button
        const postButton = document.getElementById('post-button');
        postButton.addEventListener('click', function() {
            window.location.href = 'post.php'; // Redirect to post.php when the button is clicked
        });
    </script>
</body>
</html>
