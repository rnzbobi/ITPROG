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
    // Get the item ID from the URL
    $item_id = $_GET['item_id'];
    
    // Retrieve item details from the database based on the item ID
    $sql_item = "SELECT * FROM individual_clothes WHERE id = $item_id";
    $result_item = executeQuery($conn, $sql_item);
    
    if(mysqli_num_rows($result_item) > 0) {
        // Fetch item details
        $row = mysqli_fetch_assoc($result_item);
        
        // Get user ID from session
        $username = $_SESSION['username'];
        
        // Check if user ID exists in the user_id table
        $sql_user = "SELECT userid FROM user_id WHERE username = '$username'";
        $result_user = executeQuery($conn, $sql_user);
        
        if(mysqli_num_rows($result_user) > 0) {
            $user_row = mysqli_fetch_assoc($result_user);
            $user_id = $user_row['userid'];
            
            // Add item to the cart table
            $sql_cart = "INSERT INTO carts (user_id, item_id, quantity) VALUES ('$user_id', '$item_id', 1)";
            $result_cart = executeQuery($conn, $sql_cart);
            
            // Redirect back to index.php after successful addition to cart
            header("Location: index.php");
            exit();
        } else {
            // User ID not found
            header("Location: index.php");
            exit();
        }
    } else {
        // Item not found in the database
        header("Location: index.php");
        exit();
    }
} else {
    // Item ID not provided in the URL
    header("Location: index.php");
    exit();
}
?>
