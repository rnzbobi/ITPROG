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
    <div id="postModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <form action="post.php" method="POST">
                <label for="caption">Caption:</label><br>
                <input type="text" id="caption" name="caption" required><br>
                <label for="imageUrl">Image URL:</label><br>
                <input type="url" id="imageUrl" name="imageUrl" required><br><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
    <script>
        // Get the modal
        const modal = document.getElementById('postModal');

        // Get the button that opens the modal
        const postButton = document.getElementById('post-button');

        // Get the <span> element that closes the modal
        const closeBtn = document.getElementsByClassName('close')[0];

        // When the user clicks the button, open the modal 
        postButton.onclick = function() {
            modal.style.display = 'block';
        }

        // When the user clicks on <span> (x), close the modal
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
