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
    $combo_id = $_GET['combo_id'];
    $user_id = $_GET['user_id'];
    $quantity = $_GET['quantity'];
    $operation = $_GET['operation'];
    if($operation == "add" && $combo_id == NULL){
        $add_quantity = mysqli_query($conn, "UPDATE carts SET quantity = quantity + 1  WHERE item_id=$item_id AND user_id=$user_id"); 
    }
    elseif($operation == "add" && $item_id == NULL){
        $add_quantity = mysqli_query($conn, "UPDATE carts SET quantity = quantity + 1  WHERE combo_id=$combo_id AND user_id=$user_id"); 
    }
    elseif($operation =="subtract" && $quantity>0 && $combo_id==NULL){
        $subtract_quantity = mysqli_query($conn, "UPDATE carts SET quantity = quantity - 1  WHERE item_id=$item_id AND user_id=$user_id"); 
        
        $delete_zero =
        "DELETE from carts
        WHERE quantity=0";
        mysqli_query($conn, $delete_zero);
    } 
    elseif($operation =="subtract" && $quantity>0 && $item_id==NULL){
        $subtract_quantity = mysqli_query($conn, "UPDATE carts SET quantity = quantity - 1  WHERE combo_id=$combo_id AND user_id=$user_id"); 
        
        $delete_zero =
        "DELETE from carts
        WHERE quantity=0";
        mysqli_query($conn, $delete_zero);
    } 
}
header("Location: view_cart.php");

?>