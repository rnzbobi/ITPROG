<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the post ID and user ID
$post_id = $_POST['post_id'];
$username = $_SESSION['username'];

// Get the user ID from the session
$sql = "SELECT userid FROM user_id WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$user_id = $row['userid'];

// Check if the user has already liked the post
$sql = "SELECT * FROM likes WHERE post_id = $post_id AND user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // User has already liked the post, remove the like
    $sql = "DELETE FROM likes WHERE post_id = $post_id AND user_id = $user_id";
    mysqli_query($conn, $sql);
} else {
    // User hasn't liked the post, insert a new like
    $sql = "INSERT INTO likes (post_id, user_id) VALUES ($post_id, $user_id)";
    mysqli_query($conn, $sql);
}

// Redirect back to social-media.php
header("Location: social-media.php");
exit();
?>