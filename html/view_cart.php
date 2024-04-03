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
</head>
<body>

<header>
        <div class="header">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="Logo">
                </a>
            </div>
            <form class="search-form" action="index.php?search=" method="GET">
                <input type="text" name="search" class="view_cart-search-input" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
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
                <h2>Balance: <span id="balance-value"><?php 
                $getBalance = mysqli_query($conn,
                    "SELECT user_id.balance AS 'balanceUser'
                    FROM user_id 
                        LEFT JOIN carts ON user_id.userid = carts.user_id
                        LEFT JOIN individual_clothes ON carts.item_id = individual_clothes.id
                    WHERE user_id.username='$username'");
                if ($getBalance && mysqli_num_rows($getBalance) > 0){
                    $balanceRow = mysqli_fetch_assoc($getBalance);
                    $balance = $balanceRow['balanceUser'];
                    echo $balance;
                }
                else{
                    echo 0;
                }?></span></h2>
            </div>
        </div>
    </header>
<?php
    $initializeCart = mysqli_query($conn,
    "SELECT *, SUM(c.quantity) AS total_quantity
    FROM user_id ui 
    LEFT JOIN carts c ON ui.userid = c.user_id 
    LEFT JOIN individual_clothes ic ON c.item_id = ic.id
    LEFT JOIN combo_clothes cc ON c.combo_id = cc.combo_id
    WHERE ui.username = '$username'
    GROUP BY c.item_id, c.combo_id, ic.size;");

    while ($getSum = mysqli_fetch_assoc($initializeCart)){
        $isItemDeletionExecuted = false;
        $isComboDeletionExecuted = false;
        $item_id = $getSum['item_id'];
        $combo_id = $getSum['combo_id'];
       // $item_size = $getSum['size'];
        $item_quantity = $getSum['total_quantity'];

        $updateItemQuantity = 
        "UPDATE carts 
        SET quantity = '$item_quantity'
        WHERE item_id = '$item_id'
            AND user_id = (SELECT userid FROM user_id WHERE username='$username');";
        mysqli_query($conn, $updateItemQuantity);

        if (!$isItemDeletionExecuted){
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

            $isItemDeletionExecuted = true;
        }
        $updateComboQuantity = 
        "UPDATE carts 
        SET quantity = '$item_quantity'
        WHERE combo_id = '$combo_id'
            AND user_id = (SELECT userid FROM user_id WHERE username='$username');";
        mysqli_query($conn, $updateComboQuantity);

        if (!$isComboDeletionExecuted){
            $deleteDuplicateCombo =
            "DELETE FROM carts
            WHERE combo_id = '$combo_id'
                AND user_id = (SELECT userid FROM user_id WHERE username='$username')
                AND cart_id NOT IN(
                    SELECT MIN(cart_id)
                    FROM carts 
                    WHERE combo_id='$combo_id'
                        AND user_id = (SELECT userid FROM user_id WHERE username='$username')
                        );";
            mysqli_query($conn, $deleteDuplicateCombo);

            $isComboDeletionExecuted = true;
        }
    }
    


    $getuserCart = mysqli_query($conn,
    "SELECT *, combo_clothes.image_URL AS 'combo_image', individual_clothes.image_URL AS 'item_image',
            combo_clothes.combo_name AS 'combo_name', individual_clothes.name AS 'item_name',
            combo_clothes.price AS 'combo_price', individual_clothes.price AS 'item_price'
    FROM user_id 
            LEFT JOIN carts ON user_id.userid = carts.user_id
            LEFT JOIN individual_clothes ON carts.item_id = individual_clothes.id
            LEFT JOIN combo_clothes ON combo_clothes.combo_id = carts.combo_id
        WHERE user_id.username='$username'
        AND (carts.item_id IS NOT NULL or carts.combo_id IS NOT NULL)");
  
    
?>
<center>
<div class="view_cart-top">
    Your Shopping Cart
        <div class="view_cart-amountitems">
            Total items: 
            <?php
                $countItemsQuery = mysqli_query($conn,
                    "SELECT SUM(carts.quantity) AS 'total_quantity'
                    FROM user_id 
                        LEFT JOIN carts ON user_id.userid = carts.user_id
                        LEFT JOIN individual_clothes ON carts.item_id = individual_clothes.id
                        LEFT JOIN combo_clothes ON combo_clothes.combo_id = carts.combo_id
                    WHERE user_id.username='$username'");
                $countItemsRow = mysqli_fetch_assoc($countItemsQuery);
                $countItems = $countItemsRow['total_quantity'];
                echo $countItems;
                
            ?>
        </div>
</div>
<div class="view_cart">
<?php
    $subTotal=0;
    $totalPriceItem = 0;
 
    if ($getuserCart && mysqli_num_rows($getuserCart) >0){
        while($viewCart=mysqli_fetch_assoc($getuserCart)){
            echo "<div class='cart-item-holder'>";

                echo "<div class = 'cart-image-holder'>";
                if(!empty($viewCart['item_id'])){
                    echo "<a class='link-design-view_cart' href='view.php?item_id=".$viewCart['item_id']."'>";
                    echo "<img src='".$viewCart['item_image']."'style='width: 100px; height: 100px;'>";
                    echo "</a>";
                }
                elseif(!empty($viewCart['combo_id'])){
                    echo "<a class='link-design-view_cart' href='view_combo.php?item_id=".$viewCart['combo_id']."'>";
                    echo "<img src='".$viewCart['combo_image']."'style='width: 100px; height: 100px;'>";
                    echo "</a>";
                }
            
                        
                echo "</div>";

                    echo "<div class='cart-item-details'>";
                    if(!empty($viewCart['item_id'])){
                        echo "<div class = 'cart-item-name'>";
                            echo "<a class='link-design-view_cart' href='view.php?item_id=".$viewCart['item_id']."'>";
                                echo $viewCart['item_name'];
                            echo "</a>";
                        echo "</div>";

                        echo "<div class = 'cart-item-placeholder'>";
                            echo $viewCart['size'];
                        echo "</div>";

                        echo "<div class = 'cart-item-placeholder'>";
                            echo "$".$viewCart['item_price'];
                        echo "</div>";
                    }  
                    elseif(!empty($viewCart['combo_id'])){
                        echo "<div class = 'cart-item-name'>";
                            echo "<a class='link-design-view_cart' href='view_combo.php?item_id=".$viewCart['combo_id']."'>";
                                echo $viewCart['combo_name'];
                            echo "</a>";
                        echo "</div>";
                        echo "<div class = 'cart-item-placeholder'>";
                            echo "$".$viewCart['combo_price'];
                        echo "</div>";
                    }

                    echo "</div>";

                    echo "<form action='update_quantity.php' method='GET'>";
                        echo "<div class ='view_cart-editquantity' id='editquantity".$viewCart['item_id']."'>";
                            echo "<input type='hidden' name='item_id' value='".$viewCart['item_id']."'>";
                            echo "<input type='hidden' name='combo_id' value='".$viewCart['combo_id']."'>";
                            echo "<input type='hidden' name='user_id' value='".$viewCart['user_id']."'>";
                            echo "<input type='hidden' name='quantity' value='".$viewCart['quantity']."'>";
                            echo "<button class ='view_cart-minusplus' name='operation' value='subtract'>"."-"."</button>";
                            echo "<span class ='count'>".$viewCart['quantity']."</span>";
                            echo "<button class ='view_cart-minusplus' name='operation' value='add'>"."+"."</button>";
                        echo "</div>";
                    echo "</form>"; 
                    echo "<div class = 'total-price-item'>";

                
                    if(!empty($viewCart['item_id'])){
                    $totalPriceItem = $viewCart['quantity'] * $viewCart['item_price'];
                        echo "$".$totalPriceItem;
                    }
                    elseif(!empty($viewCart['combo_id'])){
                        $totalPriceItem = $viewCart['quantity'] * $viewCart['combo_price'];
                            echo "$".$totalPriceItem;
                        }
                    echo "</div>";

                    echo "<div class = 'cart-item-delete'>";
                        echo "<form action='delete_item.php' method='GET'>";
                        echo "<button class='view_cart-button' type='submit' name='Delete' value='Delete'>";
                        echo "<input type='hidden' name='combo_id' value='".$viewCart['combo_id']."'>";
                        echo "<input type='hidden' name='item_id' value='".$viewCart['item_id']."'>";
                        echo "<input type='hidden' name='user_id' value='".$viewCart['user_id']."'>";
                        echo "<img src='https://clipground.com/images/x-image-png-6.png' height='35px' width='35px' alt='delete'>";
                        echo"</button>";
                        echo "</form>";
                    echo "</div>";

                $subTotal+=$totalPriceItem;

                echo "</div>";
        } 
     
            echo "<div class='view_cart-subtotal'>";
                echo "Subtotal: $".$subTotal;
            echo "</div>";

            echo "<div class ='view_cart-bottom'>";

                echo "<form action='checkout.php' method='GET'>";
                    mysqli_data_seek($getuserCart, 0); //This goes to the very first row
                    while($viewCart = mysqli_fetch_assoc($getuserCart)){
                        echo "<input type ='hidden' name='item_id' value='".$viewCart['item_id']."'>";
                        echo "<input type ='hidden' name='combo_id' value='".$viewCart['combo_id']."'>";
                        echo "<input type ='hidden' name='user_id' value='".$viewCart['price']."'>";
                    }
                    echo "<button class='view_cart-button-bottom' value='checkout'>Checkout";
                echo "</form>";
        }
        else{
            echo "<div class='view_cart-bottom-no-items'>";
                echo "There are no items in your cart.";
            echo "</div>";
        }
                echo "<a class ='link-design-view_cart' href='index.php'>";
                    echo "<button class='view_cart-button-bottom'>Continue Shopping";
                echo "</a>";

            echo "</div>";
?>

</div>
</center>
</body>
</html>