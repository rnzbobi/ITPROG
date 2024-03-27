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
        .button{
            display: flex;
            background: white;
            border: 0px;
        }
        .button-bottom{
            border:3px solid black;
            background-color:white;
            color:black;
            font-size:20px;
            font-family: 'Open Sans', sans-serif;  
            padding: 10px 20px;
            margin: 0;
            max-height: 53px;
        }
        .button-bottom:hover{
            border:3px solid black;
            border-color:black;
            background-color:black;
            color:white; 
        }
        .bottom{
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            gap:20px;
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
            width: 40%;  
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
        .cart-item-placeholder{
            display:block;
            flex-direction:row;
            text-align: left;
            line-height: 1.2;
        }
        .cart-item-delete{
            display:block;
            flex-direction:row;
            text-align: right;
            margin-left: 17px; 
            margin-top: 12px;
            line-height: 1.2;
            margin-bottom:20px;
        }
        .cart-image-holder{
            display: flex;
            flex-direction: column;
            margin-right: 20px; 
        }
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
        .editquantity{
            display: flex;
            height: 50px;
            min-width: 80px;
            border: 2px solid black;
            align-items: center;
            justify-content: center;
        }
        .minusplus{
            width: 100%;
            background:white;
            border: white;
            font-size: 20px; 
        }
        .editquantity span.count{
            font-size:20px;
        } 
        .total-price-item{
            display: flex;
            text-align: right;
            font-size:20px;
            margin-left:40px;
            margin-bottom:20px;
            min-width:100px;
            align-items: center;
            justify-content: center;
        }
        .separator{
            justify-content: left;
            flex-direction:column;
            font-size:20px;
        }
        .link-design{
            text-decoration: none;
            color: inherit;
            margin: 0;
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
    

    $initializeCart = mysqli_query($conn,
    "SELECT *, SUM(c.quantity) AS total_quantity
    FROM user_id ui 
    JOIN carts c ON ui.userid = c.user_id 
    JOIN individual_clothes ic ON c.item_id = ic.id
    WHERE ui.username = '$username'
    GROUP BY c.item_id, ic.size;");

    while ($getSum = mysqli_fetch_assoc($initializeCart)){
        $isDeletionExecuted = false;
        $item_id = $getSum['item_id'];
       // $item_size = $getSum['size'];
        $item_quantity = $getSum['total_quantity'];

        $updateQuantity = 
        "UPDATE carts 
        SET quantity = '$item_quantity'
        WHERE item_id = '$item_id'
            AND user_id = (SELECT userid FROM user_id WHERE username='$username');";
        mysqli_query($conn, $updateQuantity);

        if (!$isDeletionExecuted){
            $deleteDuplicate =
            "DELETE FROM carts
            WHERE item_id = '$item_id'
                AND user_id = (SELECT userid FROM user_id WHERE username='$username')
                AND cart_id NOT IN(
                    SELECT MIN(cart_id)
                    FROM carts 
                    WHERE item_id='$item_id'
                        AND user_id = (SELECT userid FROM user_id WHERE username='$username')
                        );";
            mysqli_query($conn, $deleteDuplicate);

            $isDeletionExecuted = true;
        }
    }
    


    $getuserCart = mysqli_query($conn,
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
                $countItemsQuery = mysqli_query($conn,
                    "SELECT SUM(carts.quantity) AS 'total_quantity'
                    FROM user_id 
                        JOIN carts ON user_id.userid = carts.user_id
                        JOIN individual_clothes ON carts.item_id = individual_clothes.id
                    WHERE user_id.username='$username'");
                $countItemsRow = mysqli_fetch_assoc($countItemsQuery);
                $countItems = $countItemsRow['total_quantity'];
                echo $countItems;
                
            ?>
        </div>
</div>
<div class="cart">
<?php
    $subTotal=0;
    while($viewCart=mysqli_fetch_assoc($getuserCart)){
        echo "<div class='cart-item-holder'>";

            echo "<div class = 'cart-image-holder'>";
                 echo "<img src='".$viewCart['image_URL']."'style='width: 100px; height: 100px;'>";
            echo "</div>";

                echo "<div class='cart-item-details'>";

                    echo "<div class = 'cart-item-name'>";
                        echo $viewCart['name'];
                    echo "</div>";

                    echo "<div class = 'cart-item-placeholder'>";
                        echo $viewCart['size'];
                    echo "</div>";

                    echo "<div class = 'cart-item-placeholder'>";
                        echo "$".$viewCart['price'];
                    echo "</div>";

                    
                echo "</div>";
                echo "<form action='update_quantity.php' method='GET'>";
                    echo "<div class ='editquantity' id='editquantity".$viewCart['id']."'>";
                        echo "<input type='hidden' name='item_id' value='".$viewCart['id']."'>";
                        echo "<input type='hidden' name='user_id' value='".$viewCart['user_id']."'>";
                        echo "<input type='hidden' name='quantity' value='".$viewCart['quantity']."'>";
                        echo "<button class ='minusplus' name='operation' value='subtract'>"."-"."</button>";
                        echo "<span class ='count'>".$viewCart['quantity']."</span>";
                        echo "<button class ='minusplus' name='operation' value='add'>"."+"."</button>";
                    echo "</div>";
                echo "</form>";

                echo "<div class = total-price-item>";
                $totalPriceItem =$viewCart['quantity'] * $viewCart['price'];
                    echo "$".$totalPriceItem;
                echo "</div>";

                echo "<div class = 'cart-item-delete'>";
                    echo "<form action='delete_item.php' method='GET'>";
                    echo "<button class='button' type='submit' name='Delete' value='Delete'>";
                    echo "<input type='hidden' name='item_id' value='".$viewCart['id']."'>";
                    echo "<input type='hidden' name='user_id' value='".$viewCart['user_id']."'>";
                    echo "<img src='https://clipground.com/images/x-image-png-6.png' height='35px' width='35px' alt='delete'>";
                    echo"</button>";
                    echo "</form>";
                echo "</div>";
            $subTotal+=$totalPriceItem;

            echo "</div>";
    } 
            echo "<div class='separator'>";
                echo "Subtotal: $".$subTotal;
            echo "</div>";

            echo "<div class ='bottom'>";

                echo "<form action='checkout.php' method='GET'>";
                    mysqli_data_seek($getuserCart, 0); //This goes to the very first row
                    while($viewCart = mysqli_fetch_assoc($getuserCart)){
                        echo "<input type ='hidden' name='item_id' value='".$viewCart['id']."'>";
                        echo "<input type='hidden' name='user_id' value='".$viewCart['price']."'>";
                    }
                    echo "<button class='button-bottom' value='checkout'>Checkout";
                echo "</form>";

                echo "<a class ='link-design' href='index.php'>";
                    echo "<button class='button-bottom'>Continue Shopping";
                echo "</a>";

            echo "</div>";
?>

</div>
</center>
</body>
</html>