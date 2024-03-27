<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the post ID from the form
$post_id = $_POST['post_id'];

// Get the user ID of the logged-in user
$username = $_SESSION['username'];
$sql = "SELECT userid FROM user_id WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$user_id = $row['userid'];

// Check if the user is the owner of the post
$sql = "SELECT user_id FROM posts WHERE post_id = $post_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$post_user_id = $row['user_id'];

if ($user_id == $post_user_id) {
    // User is the owner of the post

    // Delete associated likes first
    $sql = "DELETE FROM likes WHERE post_id = $post_id";
    mysqli_query($conn, $sql);

    // Then delete the post
    $sql = "DELETE FROM posts WHERE post_id = $post_id";
    mysqli_query($conn, $sql);
}

// Redirect back to social-media.php
header("Location: social-media.php");
exit();
?>