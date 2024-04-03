<?php
session_start();
include 'database.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Check if item ID is provided in the URL
if(isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $sql_item = "SELECT * FROM individual_clothes WHERE id = $item_id";
    $result_item = executeQuery($conn, $sql_item);
    
    if(mysqli_num_rows($result_item) > 0) {
        $row = mysqli_fetch_assoc($result_item);
        $username = $_SESSION['username'];
        $sql_user = "SELECT userid FROM user_id WHERE username = '$username'";
        $result_user = executeQuery($conn, $sql_user);
        
        if(mysqli_num_rows($result_user) > 0) {
            $user_row = mysqli_fetch_assoc($result_user);
            $user_id = $user_row['userid'];
            $sql_cart = "INSERT INTO carts (user_id, item_id, quantity) VALUES ('$user_id', '$item_id', 1)";
            $result_cart = executeQuery($conn, $sql_cart);
            
            // Set session variable for notification
            $_SESSION['item_added'] = true;
            
            // Redirect back to index.php
            header("Location: index.php");
            exit();
        } else {
            // User ID not found
            header("Location: index.php");
            exit();
        }
    } else {
        // Item not found
        header("Location: index.php");
        exit();
    }
} else {
    // Item ID not provided
    header("Location: index.php");
    exit();
}
?>
