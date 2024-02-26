<?php
session_start();

if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}

echo "<link rel='stylesheet' href='css/signupstyle.css' type='text/css' />";
echo "<link rel='preconnect' href='https://fonts.googleapis.com'>";
echo "<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>";
echo "<link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap' rel='stylesheet'>";

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

        $sql = "SELECT * FROM orders JOIN order_items ON orders.order_id=order_items.order_id JOIN individual_clothes 
                ON order_items.item_id=individual_clothes.id WHERE user_id='$userid'";
        $result = mysqli_query($conn, $sql);
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Purchase History</title>
            <link rel="stylesheet" href="css/style.css" type="text/css" />
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
            
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
                <a href="user.php"><img src="images/user.png" alt="User"></a>
                <a href="cart.html"><img src="images/shopping-cart.png" alt="Cart"></a>
                <?php
            if($loggedIn){
                echo '<a href="user.php"><h2><span id="user-id">Profile</span></h2></a>';
                echo '<a href="logout.php"><h2><span id="user-id">Logout</span></h2></a>';
            } else {
                echo '<a href="login.php"><h2><span id="user-id">Login/Signup</span></h2></a>';
            }
            ?>
                <h2>Balance: <span id="balance-value">10000</span></h2>
            </div>
            <!-- <div class="Balance-header">
                <h2 id="balance">Balance: <span id="balance-value">10000</span></h2>
            </div> -->
        </div>
        </header>
            <div class="container">
                <h1>Purchase History</h1>
                <div class="card-container">
                    <?php while ($purchases = mysqli_fetch_assoc($result)): ?>
                        <div class="card">
                            <div class="card-header"><h2>Order ID</h2><?php echo $purchases['order_id']; ?></div>
                            <div class="card-details">
                                <p>Date: <?php echo $purchases['order_date']; ?></p>
                                <p>Product Name: <?php echo $purchases['name']; ?></p>
                                <p>Product Brand: <?php echo $purchases['brand']; ?></p>
                                <p>Product Category: <?php echo $purchases['category']; ?></p>
                                <p>Product Color: <?php echo $purchases['color']; ?></p>
                                <p>Size: <?php echo $purchases['size']; ?></p>
                                <p>Quantity: <?php echo $purchases['quantity']; ?></p>
                                <p>Price per Unit: ₱<?php echo number_format($purchases['price_per_unit'], 2); ?></p>
                                <p>Total Cost: ₱<?php echo number_format($purchases['total_price'], 2); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </body>
        </html>

        <?php
    } else {
        echo "<h2>Error: " . mysqli_error($conn) . "</h2>";
    }
} else {
    echo "<h1>ERROR! Cannot fetch purchase history</h1>";
}
?>
