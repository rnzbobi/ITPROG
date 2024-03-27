<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the post ID from the query string
$post_id = $_GET['post_id'];

// Get the user ID of the logged-in user
$username = $_SESSION['username'];
$sql = "SELECT userid FROM user_id WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$user_id = $row['userid'];

// Check if the user is the owner of the post
$sql = "SELECT user_id, caption, image_URL FROM posts WHERE post_id = $post_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$post_user_id = $row['user_id'];

if ($user_id == $post_user_id) {
    // User is the owner of the post, display the edit form
    $caption = $row['caption'];
    $image_url = $row['image_URL'];
} else {
    // User is not the owner of the post, redirect to social-media.php
    header("Location: social-media.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="css/social_style.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="modal-content">
        <h2>Edit Post</h2>
        <form action="update_post.php" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <label for="caption">Caption:</label><br>
            <input type="text" id="caption" name="caption" value="<?php echo $caption; ?>" required><br>
            <label for="imageUrl">Image URL:</label><br>
            <input type="url" id="imageUrl" name="imageUrl" value="<?php echo $image_url; ?>" required><br><br>
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>