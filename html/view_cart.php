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
            <form class="search-form" action="index.php" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Search...">
                <button type="submit" class="search-button"><img src="images/search-interface-symbol.png" alt="Search"></button>
            </form>
            <div class="nav-links">
                <a href="social-media.html"><img src="images/social.png" alt="Social"></a>
                <a href="profile.php"><img src="images/user.png" alt="User"></a>
                <a href="view_cart.php"><img src="images/shopping-cart.png" alt="Cart"></a>
                <?php
            if($loggedIn){
                echo '<a href="profile.php"><h2><span id="user-id">Profile</span></h2></a>';
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
<h2>Your Shopping Cart</h2>
<table border="0">
<tr>
        <th></th>
        <th></th>
        <th>Quantity</th>
        <th>Total</th>
        <th></th>
</tr>

<?php
$subTotal=0;
$totalPrice=0;
    while($viewCart=mysqli_fetch_assoc($getuserCart)){
        echo "<tr>";
        echo "<td rowspan=4>"."<img src='".$viewCart['image_URL']."'style='max-width: 100px; max-height: 100px;'>"."</td>";
        echo "</tr>";
        echo "<tr style='height: 10px'>";
        echo "<td>" . $viewCart['name'] . "</td>";
        echo "</tr>";
        echo "<tr style='height: 10px'>";
        echo "<td>" . $viewCart['size'] . "</td>";
        echo "</tr>";
        echo "<tr style='height: 10px'>";
        echo "<td> $".$viewCart['price'] . "</td>";
        echo "<td>".$viewCart['quantity']."</td>";
        $totalPrice=($viewCart['price'] * $viewCart['quantity']);
        echo "<td> $".$totalPrice."</td>";
        echo "</tr>";
        $subTotal+=$totalPrice;  
    }
    echo "<td colspan=4>Subtotal: $".$subTotal."</tr>";
    echo "</table>"

   
?>
</center>
</body>
</html>