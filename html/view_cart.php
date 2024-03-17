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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
        .top{
            display: flex;
            font-family: 'Open Sans', sans-serif;  
            font-weight: bold;
            font-size: 30px;
            text-align: center; 
            justify-content: center;
            flex-direction: column;
            margin-bottom: 50px;
        }
        .amountitems{
            display: flex;
            padding:5px;
            font-size:15px;
            text-align: center; 
            justify-content: center;
        }
        .cart{
            display: flex;
            font-family: 'Open Sans', sans-serif;  
            font-size: 15px;      
            line-height: 1.4;
            flex-direction: column; 
            align-items: center;
        }
        .cart-item-holder{
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 20px;
            width: 30%;  
        }
        .cart-item-details{
            display:flex;
            flex: 1;
            flex-direction:column;
            margin-right: 20px;
        }
        .cart-item-name{
            display: flex;
            flex-direction: row;
            text-align: left;
            font-weight: bold;
            font-size: 20px;    
        }
        .cart-item-size-price{
            display:block;
            flex-direction:row;
            text-align: left;
            line-height: 1.2;
        }
        .cart-image-holder{
            display: flex;
            flex-direction: column;
            margin-right: 20px; 
        }
        .editquantity{
            display: flex;
            height: 50px;
            min-width: 80px;
            border: 2px solid black;
            align-items: center;
            justify-content: center;
        }
        .editquantity span{
            width: 100%;
            font-size: 20px; 
        }
        .editquantity span.count{
            font size:50px;
        }
    </style>
</head>
<body>
<header>
        <div class="header">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="Logo">
                </a>
            </div>
            <form class="search-form" action="index.php" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Search...">
                <button type="submit" class="search-button"><img src="images/search-interface-symbol.png" alt="Search"></button>
            </form>
            <div class="nav-links">
                <a href="social-media.php"><img src="images/social.png" alt="Social"></a>
                <a href="view_cart.php"><img src="images/shopping-cart.png" alt="Cart"></a>
                <a href="user.php"><img src="images/user.png" alt="User"></a>
                <?php
            if($loggedIn){
                echo '<a href="user.php"><h2><span id="user-id">Profile</span></h2></a>';
                echo '<a href="logout.php"><h2><span id="user-id">Logout</span></h2></a>';
            } else {
                echo '<a href="login.php"><h2><span id="user-id">Login/Signup</span></h2></a>';
            }
            ?>

            </div>
            <div class="Balance-header">
                <h2 id="balance">Balance: <span id="balance-value">10000</span></h2>
            </div>
        </div>
    </header>  
<?php
    $getuserCart = 
    mysqli_query($conn,
    "SELECT *
    FROM user_id 
        JOIN carts ON user_id.userid = carts.user_id
        JOIN individual_clothes ON carts.item_id = individual_clothes.id
    WHERE user_id.username='$username'");
    
?>
<center>
<div class="top">
    Your Shopping Cart
        <div class="amountitems">
            Total items: 
            <?php
                $countItems = mysqli_num_rows ($getuserCart);
                echo $countItems;
            ?>
        </div>
</div>
<div class="cart">
<?php
$subTotal=0;
$totalPrice=0;
    while($viewCart=mysqli_fetch_assoc($getuserCart)){
        echo "<div class='cart-item-holder'>";

            echo "<div class = 'cart-image-holder'>";
                 echo "<img src='".$viewCart['image_URL']."'style='width: 100px; height: 100px;'>";
            echo "</div>";

                echo "<div class='cart-item-details'>";

                    echo "<div class = 'cart-item-name'>";
                        echo $viewCart['name'];
                    echo "</div>";

                    echo "<div class = 'cart-item-size-price'>";
                        echo $viewCart['size'];
                    echo "</div>";

                    echo "<div class = 'cart-item-size-price'>";
                        echo $viewCart['price'];
                    echo "</div>";

                    
                echo "</div>";

        echo "<div class ='editquantity' id='editquantity".$viewCart['id']."'>";
            echo "<span class ='minus'>"."-"."</span>";
            echo "<span class ='count'>".$viewCart['quantity']."</span>";
            echo "<span class ='plus'>"."+"."</span>";
        echo "</div>";

     echo "</div>";
?>
<script>
    const plus<?php echo $viewCart['id']; ?> = document.querySelector("#editquantity<?php echo $viewCart['id']; ?> .plus");
    const minus<?php echo $viewCart['id']; ?> = document.querySelector("#editquantity<?php echo $viewCart['id']; ?> .minus");
    const count<?php echo $viewCart['id']; ?> = document.querySelector("#editquantity<?php echo $viewCart['id']; ?> .count");

    plus<?php echo $viewCart['id']; ?>.addEventListener("click", function(){
        count<?php echo $viewCart['id']; ?>.textContent = parseInt(count<?php echo $viewCart['id']; ?>.textContent) + 1;
    });

    minus<?php echo $viewCart['id']; ?>.addEventListener("click", function(){
        if (parseInt(count<?php echo $viewCart['id']; ?>.textContent) > 0){
        count<?php echo $viewCart['id']; ?>.textContent = parseInt(count<?php echo $viewCart['id']; ?>.textContent) - 1;
        }
    });
</script>
<?php
    } 
?>

</div>
</center>




</body>
</html>