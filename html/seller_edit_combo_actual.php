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
    <title>Edit an Item</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel='stylesheet' href='css/signupstyle.css' type='text/css' />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
<?php
    $combo_id = $_GET['combo_id'];
    $combo_name = $_GET['combo_name'];
        /*$clothes_name = $_GET['name'];
        $clothes_size = $_GET['size'];
        $clothes_size = $_GET['brand'];
        $clothes_cat = $_GET['category'];
        $clothes_color = $_GET['color'];
        $clothes_gender = $_GET['gender'];
        $clothes_price = $_GET['price'];
        $clothes_quantity = $_GET['quantity'];
        $clothes_url = $_GET['url'];
        $clothes_desc = $_GET['description'];*/

    $getClothes = mysqli_query($conn, 
                "SELECT * 
                FROM individual_clothes");

    $getCombotoEdit = mysqli_query($conn, 
                    "SELECT *, individual_clothes1.name AS item1_name,
                               individual_clothes2.name AS item2_name,
                               individual_clothes3.name AS item3_name,
                               individual_clothes4.name AS item4_name,
                               combo_clothes.price AS 'price',
                               combo_clothes.available_quantity AS 'available_quantity',
                               combo_clothes.image_URL AS 'image_URL',
                               combo_clothes.description AS 'description'
                     FROM combo_clothes
                     LEFT JOIN individual_clothes AS individual_clothes1 ON combo_clothes.item_id1=individual_clothes1.id
                     LEFT JOIN individual_clothes AS individual_clothes2 ON combo_clothes.item_id2=individual_clothes2.id
                     LEFT JOIN individual_clothes AS individual_clothes3 ON combo_clothes.item_id3=individual_clothes3.id
                     LEFT JOIN individual_clothes AS individual_clothes4 ON combo_clothes.item_id4=individual_clothes4.id
                     WHERE combo_id=$combo_id");
                     
    
?>


<center>

<div class="eia-interface"> 
    <div class="eia-title"> <b>Combo to Edit: <?php echo $combo_name?></b></div>
    <form method="POST" action="seller_edit_combo_action.php" class="eia-item-form">
        <?php
        
            while($editCombo=mysqli_fetch_assoc($getCombotoEdit)){
                echo "<div class='eia-form-group'>";
                    echo "<input type='hidden' id='combo_id' name='combo_id' value='".$editCombo['combo_id']."'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='combo_name'>Name:</label>";
                    echo "<input type='text' id='combo_name' name='combo_name' value='".$editCombo['combo_name']."'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='item_id1'>Item ID1:</label>";
                    echo "<select id='item_id1' name='item_id1' required>";
                    while ($obtainClothesList=mysqli_fetch_assoc($getClothes)){
                        if($obtainClothesList['name']!==$editCombo['item1_name']){
                            echo "<option value='".$obtainClothesList['id']."'>".$obtainClothesList['name']."</option>";
                        }
                        else{
                            echo "<option value='".$obtainClothesList['id']."' style='background-color: green'>".$obtainClothesList['name']."</option>";
                            }
    
                    }
                    echo "</select>";
                echo "</div>";

            mysqli_data_seek($getClothes, 0);
            echo "<div class='eia-form-group'>";
                echo "<label for='item_id2'>Item ID2:</label>";
                echo "<select id='item_id2' name='item_id2' required>";
                echo "<option value='".$editCombo['item_id2']."' selected>".$editCombo['item2_name']."</option>";
                while ($obtainClothesList=mysqli_fetch_assoc($getClothes)){
                    if($obtainClothesList['name']!==$editCombo['item2_name']){
                        echo "<option value='".$obtainClothesList['id']."'>".$obtainClothesList['name']."</option>";
                        }
                        else{
                        echo "<option value='".$obtainClothesList['id']."' style='background-color: green'>".$obtainClothesList['name']."</option>";
                        }
                }
            echo "</select>";
            echo "</div>";

            mysqli_data_seek($getClothes, 0);
            echo "<div class='eia-form-group'>";
                echo "<label for='item_id3'>Item ID3:</label>";
                echo "<select id='item_id3' name='item_id3'>";
                echo "<option value='' selected>Choose an item</option>";
                echo "<option value='".$editCombo['item3_id']."'>".$editCombo['item3_name']."</option>";
                while ($obtainClothesList=mysqli_fetch_assoc($getClothes)){
                    if($obtainClothesList['name']!==$editCombo['item3_name']){
                        echo "<option value='".$obtainClothesList['id']."'>".$obtainClothesList['name']."</option>";
                        }
                        else{
                            echo "<option value='".$obtainClothesList['id']."' style='background-color: green'>".$obtainClothesList['name']."</option>";
                            }
                }
            echo "</select>";
            echo "</div>";

            mysqli_data_seek($getClothes, 0);
            echo "<div class='eia-form-group'>";
                echo "<label for='item_id4'>Item ID4:</label>";
                echo "<select id='item_id4' name='item_id4'>";
                echo "<option value='' selected>Choose an item</option>";
                echo "<option value='NULL".$editCombo['item4_id']."' >".$editCombo['item4_name']."</option>";
                while ($obtainClothesList=mysqli_fetch_assoc($getClothes)){
                    if($obtainClothesList['name']!==$editCombo['item4_name']){
                        echo "<option value='".$obtainClothesList['id']."'>".$obtainClothesList['name']."</option>";
                        }
                        else{
                        echo "<option value='".$obtainClothesList['id']."' style='background-color: green'>".$obtainClothesList['name']."</option>";
                        }

                }
            echo "</select>";
            echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='price'>Price:</label>";
                    echo "<input type='number' id ='price' name='price' value='".$editCombo['price']."' step='0.01' min='0'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Available Quantity:</label>";
                    echo "<input type='number' id ='quantity' name='quantity' value='".$editCombo['available_quantity']."' min='0'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Image URL:</label>";
                    echo "<input type='text' id ='url' name='url' value='".$editCombo['image_URL']."' required>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Description:</label>";                
                    echo "<textarea id='description' name='description' required>".$editCombo['description']."</textarea>";
                echo "</div>";

                echo "<button class='eia-submit'>Submit Changes</button>";
                
                echo "<div class='eia-bottom'>";
                    echo "<a class='eia-regbutton' href='seller_edit_combo.php'>Back to Choices</a>";
                echo"</div>";
            } 
        ?>
    </form>
</div>
</center>

</body>
</html>
