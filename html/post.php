<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the user ID from the session
$username = $_SESSION['username'];
$sql = "SELECT userid FROM user_id WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$user_id = $row['userid'];

// Get the form data
$caption = $_POST['caption'];
$imageUrl = $_POST['imageUrl'];

// Insert the post into the database
$sql = "INSERT INTO posts (user_id, caption, image_URL) VALUES ('$user_id', '$caption', '$imageUrl')";

if (mysqli_query($conn, $sql)) {
    // Redirect back to social-media.php with the updated posts
    header("Location: social-media.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>