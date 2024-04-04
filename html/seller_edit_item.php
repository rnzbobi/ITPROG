<?php
session_start();
include 'database.php';
if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit an Item as a Seller</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel='stylesheet' href='css/signupstyle.css' type='text/css' />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>

<?php
$getClothes = mysqli_query($conn,
"SELECT *
FROM individual_clothes ic");
?>

<body>
    <center>
        <div class = "edit_item_initial-interface">
            <b>Edit an item</b>
            Select an Item to be edited.
            <div class = "edit_item_initial-container">
            <?php
             while($editClothesDisplay=mysqli_fetch_assoc($getClothes)){
                echo "<div class ='edit_item_initial_interface-card'>";
                    echo "<div class = 'edit_item_initial_interface-image'>";
                        echo "<a class='link-design-view_cart' href='view.php?item_id=".$editClothesDisplay['id']."'>";
                        echo "<img src='".$editClothesDisplay['image_URL']."'style='width: 100px; height: 100px;'>";
                        echo "</a>";
                    echo "</div>";

                    echo "<div class = 'edit_item_initial_interface-name'>";
                        echo $editClothesDisplay['name'];
                    echo "</div>";

                    echo "<div class = 'edit_item_initial_interface-size'>";
                        echo $editClothesDisplay['size'];
                    echo "</div>";
                    echo "<div class = 'edit_item_initial_interface-price'>";
                        echo $editClothesDisplay['price'];
                    echo "</div>";

                    echo "<form action='seller_edit_item_actual.php' method='GET'>";
                        echo "<input type='hidden' name='item_id' value='".$editClothesDisplay['id']."'>";
                        echo "<input type='hidden' name='name' value='".$editClothesDisplay['name']."'>";
                        echo "<input type='hidden' name='size' value='".$editClothesDisplay['size']."'>";
                       /*echo "<input type='hidden' name='brand' value='".$editClothesDisplay['brand']."'>";
                        echo "<input type='hidden' name='category' value='".$editClothesDisplay['category']."'>";
                        echo "<input type='hidden' name='color' value='".$editClothesDisplay['color']."'>";
                        echo "<input type='hidden' name='gender' value='".$editClothesDisplay['gender']."'>";
                        echo "<input type='hidden' name='price' value='".$editClothesDisplay['price']."'>";
                        echo "<input type='hidden' name='quantity' value='".$editClothesDisplay['available_quantity']."'>";
                        echo "<input type='hidden' name='url' value='".$editClothesDisplay['image_URL']."'>";
                        echo "<input type='hidden' name='description' value='".$editClothesDisplay['description']."'>";*/
                        echo "<button class='edit_item_initial_interface-delbutton' value='Edit_Seller' name='Edit_Seller'>Edit Item";
                    echo "</form>";

                echo "</div>";
             }
            ?>
            </div>
            <div class='edit_item_initial_interface-bottom'>
                <a class='edit_item_initial_interface-regbutton'  href="sellermode.php">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </center>
</body>
</body>
</html>