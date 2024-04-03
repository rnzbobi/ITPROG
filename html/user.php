<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        $loggedIn = false;
    } else {
        $loggedIn = true;
        $username = $_SESSION['username'];
    }

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    } else {

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
                        echo number_format($balance, 2); // Display balance with 2 decimal places
                    }
                    else{
                        echo number_format(0, 2); // Default to 0 with 2 decimal places
                }
                ?></span></h2>
            </div>
        </div>
        </header>

    <div class="user-info">
    <?php
        if ($result) {
            $user = mysqli_fetch_assoc($result);
            echo '<label class="labelstyle" for="name">Name:</label>';
            echo '<input class="inputstyle2" type="text" id="name" value="' . $user['name'] . '" disabled><br>';
            echo '<label class="labelstyle" for="username">Username:</label>';
            echo '<input class="inputstyle2" type="text" id="username" value="' . $user['username'] . '" disabled><br>';
            $masked_password = str_repeat('*', strlen($user['user_password']));
            echo '<label class="labelstyle" for="password">Password:</label>';
            echo '<input class="inputstyle2" type="password" id="password" value="' . $masked_password . '" disabled><br>';
            echo '<label class="labelstyle" for="balance">Balance:</label>';
            echo '<input class="inputstyle2" type="text" id="balance" value="' . number_format($user['balance'], 2) . '" disabled><br>';
            echo '<a href="editprofile.php">Edit Profile</a><br><br>';
            echo '<a href="sellermode.php">Seller Mode</a><br><br>';
            echo '<a href="viewpurchasehistory.php" style="margin-right: 10px;">View Purchase History</a>';
            echo '<a href="index.php">Go Back</a>';
        } else {
            echo "<h2>Error: " . mysqli_error($conn) . "</h2>";
        }
    }
    ?>
    </div>
</body>
</html>