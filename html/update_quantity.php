<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}

if(isset($_GET["operation"])) {
    $item_id = $_GET['item_id'];
    $user_id = $_GET['user_id'];
    $quantity = $_GET['quantity'];
    $operation = $_GET['operation'];
    if ($operation == "add"){
        $add_quantity = mysqli_query($conn, "UPDATE carts SET quantity = quantity + 1  WHERE item_id=$item_id AND user_id=$user_id"); 

    }
    elseif($operation =="subtract" && $quantity>0){
        $subtract_quantity = mysqli_query($conn, "UPDATE carts SET quantity = quantity - 1  WHERE item_id=$item_id AND user_id=$user_id"); 
        
        $delete_zero =
        "DELETE from carts
        WHERE quantity=0";
        mysqli_query($conn, $delete_zero);
    } 

}
header("Location: view_cart.php");

?>