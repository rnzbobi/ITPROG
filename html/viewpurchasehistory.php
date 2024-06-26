<?php
session_start();

if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}

if (isset($_SESSION['username'])) {
    $conn = mysqli_connect("localhost", "root", "") or die ("Unable to connect!". mysqli_error());
    mysqli_select_db($conn, "dbclothes");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username already exists in the database
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM user_id WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        $userid = $user['userid'];
        $purchases = array();

        $sql = "SELECT * FROM orders 
        LEFT JOIN order_items ON orders.order_id = order_items.order_id 
        LEFT JOIN individual_clothes ON order_items.item_id = individual_clothes.id 
        WHERE user_id = '$userid' 
        ORDER BY orders.order_id DESC";
        $result = mysqli_query($conn, $sql);
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Purchase History</title>
            <link rel="stylesheet" href="css/style.css" type="text/css" />
            <link rel='stylesheet' href='css/signupstyle.css' type='text/css' />
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
        </head>
        <body class="bodystyle">
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
                    echo "$" . number_format($balance, 2); // Display balance with dollar sign and 2 decimal places
                }
                else{
                    echo "$" . number_format(0, 2); // Default to $0.00 if balance not found
                }
            ?></span></h2>
            </div>
        </div>
        </header>
        <h1 class="h1style">Purchase History for <?php echo $user['name'];?></h1>
        <div class="container">
                <div class="card-container">
                    <?php while ($purchases = mysqli_fetch_assoc($result)): ?>
                        <?php $comboid = $purchases['combo_id']; ?>
                        <?php if($comboid !== null) {
                            $sql2 = "SELECT * FROM combo_clothes WHERE combo_id='$comboid'";
                            $result2 = mysqli_query($conn, $sql2);

                            $combo = mysqli_fetch_assoc($result2);
                        }?>
                        <div class="card">
                            <div class="card-header"><h2>Order ID</h2><?php echo $purchases['order_id']?></div>
                            <div class="card-details">
                                <?php if($purchases['combo_id'] !== null) { ?>
                                    <a href="view_combo.php?item_id=<?php echo $combo['combo_id']; ?>">
                                        <img src=<?php echo $combo['image_URL']; ?> class="card-image">
                                    </a>
                                    <p class="pstyle">Date: <?php echo $purchases['order_date']; ?></p>
                                    <p class="pstyle">Product Name: <?php echo $combo['combo_name']; ?></p>
                                    <p class="pstyle">Quantity: <?php echo $purchases['quantity']; ?></p>
                                    <p class="pstyle">Total Cost: $<?php echo number_format($purchases['total_price'], 2); ?></p>
                                <?php } else { ?>
                                <a href="view.php?item_id=<?php echo $purchases['id']; ?>">
                                    <img src=<?php echo $purchases['image_URL']; ?> class="card-image">
                                </a>
                                <p class="pstyle">Date: <?php echo $purchases['order_date']; ?></p>
                                <p class="pstyle">Product Name: <?php echo $purchases['name']; ?></p>
                                <p class="pstyle">Product Brand: <?php echo $purchases['brand']; ?></p>
                                <p class="pstyle">Product Category: <?php echo $purchases['category']; ?></p>
                                <p class="pstyle">Product Color: <?php echo $purchases['color']; ?></p>
                                <p class="pstyle">Size: <?php echo $purchases['size']; ?></p>
                                <p class="pstyle">Quantity: <?php echo $purchases['quantity']; ?></p>
                                <p class="pstyle">Total Cost: $<?php echo number_format($purchases['total_price'], 2); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            </div>
        </body>
        </html>

        <?php
    } else {
        echo "<h2>Error: " . mysqli_error($conn) . "</h2>";
    }
} else {
    echo "<h1 class='h1style'>ERROR! Cannot fetch purchase history</h1>";
}
?>
