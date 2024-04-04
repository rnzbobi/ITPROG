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
    $item_id = $_GET['delete_seller_itemid'];
    $combo_id = $_GET['combo_id'];
    $user_id = $_GET['user_id'];
    if($combo_id == NULL){
        $delete_item = mysqli_query($conn, "DELETE FROM carts WHERE item_id=$item_id AND user_id=$user_id"); 
    }
    elseif($item_id == NULL){
        $delete_item = mysqli_query($conn, "DELETE FROM carts WHERE combo_id=$combo_id AND user_id=$user_id"); 
    }
    header("Location: view_cart.php");
}
elseif(isset($_GET["Delete_Seller_Item"])){
    $item_id = $_GET['delete_seller_itemid'];
    $delete_item = mysqli_query($conn, "DELETE FROM individual_clothes WHERE id=$item_id"); 
    header("Location: seller_deleteitem.php");
}
elseif(isset($_GET["Delete_Seller_Combo"])){
    $combo_id = $_GET['delete_seller_comboid'];
    $delete_item = mysqli_query($conn, "DELETE FROM combo_clothes WHERE combo_id=$combo_id"); 
    header("Location: seller_deletecombo.php");
}

?>