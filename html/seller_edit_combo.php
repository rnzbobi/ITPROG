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
    <meta charset="UTF-8"> <title>Edit a Combo as a Seller</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel='stylesheet' href='css/signupstyle.css' type='text/css' />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php
$getCombo = mysqli_query($conn,
"SELECT *
FROM combo_clothes");
?>

<body>
    <center>
        <div class = "ec_initial-interface">
            <b>Edit an item</b>
            Select an Item to be edited.
            <div class = "ec_initial-container">
            <?php
             while($editComboDisplay=mysqli_fetch_assoc($getCombo)){
                echo "<div class ='ec_initial_interface-card'>";
                    echo "<div class = 'ec_initial_interface-image'>";
                        echo "<a class='link-design-view_cart' href='view_combo.php?item_id=".$editComboDisplay['combo_id']."'>";
                        echo "<img src='".$editComboDisplay['image_URL']."'style='width: 100px; height: 100px;'>";
                        echo "</a>";
                    echo "</div>";

                    echo "<div class = 'ec_initial_interface-name'>";
                        echo $editComboDisplay['combo_name'];
                    echo "</div>";

                    echo "<div class = 'ec_initial_interface-size'>";
                        //echo $editComboDisplay['size'];
                    echo "</div>";
                    echo "<div class = 'ec_initial_interface-price'>";
                        echo $editComboDisplay['price'];
                    echo "</div>";

                    echo "<form action='seller_edit_combo_actual.php' method='GET'>";
                        echo "<input type='hidden' name='combo_id' value='".$editComboDisplay['combo_id']."'>";
                         echo "<input type='hidden' name='combo_name' value='".$editComboDisplay['combo_name']."'>";
                       /*echo "<input type='hidden' name='size' value='".$editComboDisplay['size']."'>";
                       echo "<input type='hidden' name='brand' value='".$editClothesDisplay['brand']."'>";
                        echo "<input type='hidden' name='category' value='".$editClothesDisplay['category']."'>";
                        echo "<input type='hidden' name='color' value='".$editClothesDisplay['color']."'>";
                        echo "<input type='hidden' name='gender' value='".$editClothesDisplay['gender']."'>";
                        echo "<input type='hidden' name='price' value='".$editClothesDisplay['price']."'>";
                        echo "<input type='hidden' name='quantity' value='".$editClothesDisplay['available_quantity']."'>";
                        echo "<input type='hidden' name='url' value='".$editClothesDisplay['image_URL']."'>";
                        echo "<input type='hidden' name='description' value='".$editClothesDisplay['description']."'>";*/
                        echo "<button class='ec_initial_interface-delbutton' value='Edit_Seller_Combo' name='Edit_Seller_Combo'>Edit Combo";
                    echo "</form>";

                echo "</div>";
             }
            ?>
            </div>
            <div class='ec_initial_interface-bottom'>
                <a class='ec_initial_interface-regbutton'  href="sellermode.php">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </center>
</body>
</body>
</html>