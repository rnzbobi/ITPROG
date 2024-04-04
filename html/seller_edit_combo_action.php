<?php
session_start();
include 'database.php';
if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $combo_id=$_POST['combo_id'];
    $combo_name=$_POST['combo_name'];
    $item_id1 = isset($_POST['item_id1']) ? $_POST['item_id1'] : 'NULL';
    $item_id2 = isset($_POST['item_id2']) ? $_POST['item_id2'] : 'NULL';
    $item_id3 = isset($_POST['item_id3']) ? $_POST['item_id3'] : 'NULL';
    $item_id4 = isset($_POST['item_id4']) ? $_POST['item_id4'] : 'NULL';
    /*$item_id1 = $_POST['item_id1'];
    $item_id2 = $_POST['item_id2'];
    $item_id3 = $_POST['item_id3'];
    $item_id4 = $_POST['item_id4'];*/
    $combo_price=$_POST['price'];
    $combo_quantity=$_POST['quantity'];
    $combo_URL=$_POST['url'];
    $combo_desc=$_POST['description'];
}

$deleteCartsQuery = "DELETE FROM order_items WHERE combo_id = $combo_id";
if (mysqli_query($conn, $deleteCartsQuery)){
    $deleteOrdersQuery = "DELETE FROM carts WHERE combo_id = $combo_id";
    if (mysqli_query($conn, $deleteOrdersQuery)){
        $deleteQuery = "DELETE FROM combo_clothes WHERE combo_id = $combo_id";
        $deleteResult = mysqli_query($conn, $deleteQuery);
        if ($deleteResult && !empty($item_id4) && !empty($item_id3)){
            $addAgain="INSERT INTO combo_clothes (combo_name, item_id1, item_id2, item_id3, item_id4, price, available_quantity, image_URL, description) 
            VALUES ('$combo_name', '$item_id1', '$item_id2', '$item_id3', '$item_id4', '$combo_price', '$combo_quantity', '$combo_URL', '$combo_desc')";
                if (mysqli_query($conn, $addAgain)) {
                    header("Location: seller_edit_combo.php");
                }
        }
        elseif ($deleteResult && empty($item_id4) && !empty($item_id3)){
            $addAgain="INSERT INTO combo_clothes (combo_name, item_id1, item_id2, item_id3, price, available_quantity, image_URL, description) 
            VALUES ('$combo_name', '$item_id1', '$item_id2', '$item_id3', '$combo_price', '$combo_quantity', '$combo_URL', '$combo_desc')";
                if (mysqli_query($conn, $addAgain)) {
                    header("Location: seller_edit_combo.php");
                }
        }
        elseif ($deleteResult && empty($item_id4) && empty($item_id3)){
            $addAgain="INSERT INTO combo_clothes (combo_name, item_id1, item_id2, price, available_quantity, image_URL, description) 
            VALUES ('$combo_name', '$item_id1', '$item_id2', '$combo_price', '$combo_quantity', '$combo_URL', '$combo_desc')";
                if (mysqli_query($conn, $addAgain)) {
                    header("Location: seller_edit_combo.php");
                }
        }
    }
    }
?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit an Item</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel='stylesheet' href='css/signupstyle.css' type='text/css' />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
</body>
</html>