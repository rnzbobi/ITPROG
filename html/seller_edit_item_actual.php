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
    $clothes_id = $_GET['item_id'];
    $clothes_name = $_GET['name'];
    $clothes_size = $_GET['size'];
        /*$clothes_size = $_GET['brand'];
        $clothes_cat = $_GET['category'];
        $clothes_color = $_GET['color'];
        $clothes_gender = $_GET['gender'];
        $clothes_price = $_GET['price'];
        $clothes_quantity = $_GET['quantity'];
        $clothes_url = $_GET['url'];
        $clothes_desc = $_GET['description'];*/

    $getClothestoEdit = mysqli_query($conn, 
                    "SELECT *
                     FROM individual_clothes
                     WHERE id=$clothes_id");
                     
    
?>
<center>

<div class="eia-interface"> 
    <div class="eia-title"> <b>Item to Edit: <?php echo $clothes_name." Size ".$clothes_size?></b></div>
    <form method="POST" action="seller_edit_item_action.php" class="eia-item-form" enctype="multipart/form-data">

        <?php
            while($editClothes=mysqli_fetch_assoc($getClothestoEdit)){
                echo "<div class='eia-form-group'>";
                    echo "<input type='hidden' id='item_id' name='item_id' value='".$editClothes['id']."'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Item Name:</label>";
                    echo "<input type='text' id ='name' name='name' value='".$editClothes['name']."' required maxlength='45'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Brand:</label>";
                    echo "<input type='text' id ='brand' name='brand' value='".$editClothes['brand']."' required maxlength='45'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Category:</label>";
                    echo "<input type='text' id ='category' name='category' value='".$editClothes['category']."' required maxlength='45'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Color:</label>";
                    echo "<input type='text' id ='color' name='color' value='".$editClothes['color']."' required maxlength='45'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Gender:</label>";
                    echo "<input type='text' id ='gender' name='gender' value='".$editClothes['gender']."' required maxlength='45'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Size:</label>";
                    echo "<input type='text' id ='size' name='size' value='".$editClothes['size']."' required maxlength='3'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Price:</label>";
                    echo "<input type='number' id ='price' name='price' value='".$editClothes['price']."' step='0.01' min='0'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Available Quantity:</label>";
                    echo "<input type='number' id ='quantity' name='quantity' value='".$editClothes['available_quantity']."' min='0'>";
                echo "</div>";

                echo "<div class='eia-form-group'>";
                    echo "<label for='image_URL' id='drop-area-edit-item' class='drop-area-edit-item'> Drag and drop an image to be displayed for the combo.";
                        echo  "<input type='file' id='image_URL' name='image_URL'  value='".$editClothes['image_URL']."' accept='image/*' required hidden >";
                            echo  "<div class='edit-item-image-area' id='edit-item-image-area'>";
                                echo  "<div class='edit-item-image-container' id ='edit-item-image-container'></div>";
                            echo "</div>";
                   // echo "<input type='text' id ='url' name='url' value='".$editClothes['image_URL']."' required>";
                echo "</div>";
        ?>

<script>
            const dragFile = document.getElementById("image_URL");
            const displayImage = document.getElementById("edit-item-image-area");
            const dropArea = document.getElementById("drop-area-edit-item");

            function uploadImage(){
                const file = dragFile.files[0];
                handleFile(file);
                //displayImage.style.backgroundImage=`url(${imgLink})`;
                //displayImage.textContent="";
            }

        dragFile.addEventListener("change", (e) =>{
            const file = e.target.files[0];
            uploadImage(file);
        });

        dropArea.addEventListener('dragover', (e) =>{
            if (e.target.id !== 'drop-area-edit-item') {
             e.preventDefault();
            }
        });

        dropArea.addEventListener('drop', (e) =>{
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            handleFile(file);
        });

        function handleFile(file){
            if (file && file.type.startsWith('image/')){
                const reader = new FileReader();
                reader.onload = function (e){
                    const imageContainer = document.getElementById("edit-item-image-container");
                    imageContainer.style.backgroundImage=`url(${e.target.result})`;
                    imageContainer.textContent="";
                }
                reader.readAsDataURL(file);
            }
            else{
                alert('Please select an image file');
            }
        }
</script>

        <?php

                echo "<div class='eia-form-group'>";
                    echo "<label for='name'>Description:</label>";                
                    echo "<textarea id='description' name='description' required>".$editClothes['description']."</textarea>";
                echo "</div>";

                echo "<button class='eia-submit'>Submit Changes</button>";
                
                echo "<div class='eia-bottom'>";
                    echo "<a class='eia-regbutton' href='seller_edit_item.php'>Back to Choices</a>";
                echo"</div>";
            } 
        ?>
    </form>
</div>
</center>

</body>
</html>
