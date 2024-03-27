<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}  
if(isset($_GET["Delete"])) {
    $item_id = $_GET['item_id'];
    $user_id = $_GET['user_id'];
    $delete_item = mysqli_query($conn, "DELETE FROM carts WHERE item_id=$item_id AND user_id=$user_id"); 

    header("Location: view_cart.php");
}

?>