<?php
session_start();
include 'database.php';
if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}

$getClothes = mysqli_query($conn,
"SELECT *
FROM individual_clothes ic");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete an Item as a Seller</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <center>
        <div class = "delete_interface">
            <b>Delete an item</b>
            Select an Item to be deleted.
            <div class = "delete_interface-container">
            <?php
             while($deleteClothesDisplay=mysqli_fetch_assoc($getClothes)){
                echo "<div class ='delete_interface-card'>";
                    echo "<div class = 'delete_interface-image'>";
                        echo "<a class='link-design-view_cart' href='view.php?item_id=".$deleteClothesDisplay['id']."'>";
                        echo "<img src='".$deleteClothesDisplay['image_URL']."'style='width: 100px; height: 100px;'>";
                        echo "</a>";
                    echo "</div>";

                    echo "<div class = 'delete_interface-name'>";
                        echo $deleteClothesDisplay['name'];
                    echo "</div>";

                    echo "<div class = 'delete_interface-size'>";
                        echo $deleteClothesDisplay['size'];
                    echo "</div>";
                    echo "<div class = 'delete_interface-price'>";
                        echo $deleteClothesDisplay['price'];
                    echo "</div>";

                    echo "<form action='delete_item.php' method='GET'>";
                        echo "<input type='hidden' name='delete_seller_itemid' value='".$deleteClothesDisplay['id']."'>";
                        echo "<button class='delete_interface-delbutton' value='Delete_Seller_Item' name='Delete_Seller_Item'>Delete Item";
                    echo "</form>";

                echo "</div>";
             }
            ?>
            </div>
            <div class='delete_interface-bottom'>
                <a class='delete_interface-regbutton'  href="sellermode.php">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </center>
</body>
</html>