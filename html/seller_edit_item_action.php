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
    $target_dir="uploads/";
    $target_file= $target_dir.basename($_FILES["image_URL"]["name"]);
    $uploadOk=1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    

    $clothes_id = $_POST['item_id'];
    $clothes_name = $_POST['name'];
    $clothes_size = $_POST['size'];
    $clothes_brand = $_POST['brand'];
    $clothes_category = $_POST['category'];
    $clothes_color = $_POST['color'];
    $clothes_gender = $_POST['gender'];
    $clothes_price = $_POST['price'];
    $clothes_quantity = $_POST['quantity'];
    $clothes_url = $_POST['url'];
    $clothes_desc = $_POST['description'];

    if ($_FILES['image_URL']['error']==UPLOAD_ERR_OK){
        $temp_name=$_FILES['image_URL']['tmp_name'];
        $new_name='uploads/'.uniqid().'_'.$_FILES['image_URL']['name'];
        move_uploaded_file($temp_name,$new_name);
        $clothes_url=$new_name;
    }else{
        $clothes_url=$_POST['image_URL'];
    }
}

$updateQuery=
"UPDATE individual_clothes 
SET 
    name = '$clothes_name',
    brand = '$clothes_brand',
    category = '$clothes_category',
    color = '$clothes_color',
    gender = '$clothes_gender',
    size = '$clothes_size',
    price = '$clothes_price',
    available_quantity = '$clothes_quantity',
    image_URL = '$clothes_url',
    description = '$clothes_desc'
WHERE id=$clothes_id";

if(mysqli_query($conn, $updateQuery)){
    header("Location: seller_edit_item.php");
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