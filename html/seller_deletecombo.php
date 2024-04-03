<?php
session_start();
include 'database.php';
if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}


$getCombo = mysqli_query($conn,
"SELECT *
FROM combo_clothes");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete a combo as a Seller</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <center>
        <div class = "delete_interface">
            <b>Delete a combo</b>
            Select a combo to be deleted.
            <div class = "delete_interface-container">
            <?php
             while($deleteComboDisplay=mysqli_fetch_assoc($getCombo)){
                echo "<div class ='delete_interface-card'>";
                    echo "<div class = 'delete_interface-image'>";
                        echo "<a class='link-design-view_cart' href='view.php?item_id=".$deleteComboDisplay['combo_id']."'>";
                        echo "<img src='".$deleteComboDisplay['image_URL']."'style='width: 100px; height: 100px;'>";
                        echo "</a>";
                    echo "</div>";

                    echo "<div class = 'delete_interface-name'>";
                        echo $deleteComboDisplay['combo_name'];
                    echo "</div>";

                    /*echo "<div class = 'delete_interface-size'>";
                        echo $deleteComboDisplay['size'];
                    echo "</div>";*/
                    echo "<div class = 'delete_interface-price'>";
                        echo $deleteComboDisplay['price'];
                    echo "</div>";

                    echo "<form action='delete_item.php' method='GET'>";
                        echo "<input type='hidden' name='delete_seller_comboid' value='".$deleteComboDisplay['combo_id']."'>";
                        echo "<button class='delete_interface-delbutton' value='Delete_Seller_Combo' name='Delete_Seller_Combo'>Delete Combo";
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