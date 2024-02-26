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
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index.html</title>
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
    <?php
        if ($result) {
            $user = mysqli_fetch_assoc($result);
            echo "<h2>Name: ". $user['name'] . "</h2><br>";
            echo "<h2>Username: ". $user['username'] . "</h2><br>";
            $masked_password = str_repeat('*', strlen($user['user_password']));
            echo "<h2>Password: " . $masked_password . "</h2><br>";
            echo "<h2>Balance: ". $user['balance'] . "</h2><br>";
            echo '<a href="viewpurchasehistory.php"><h2>View Purchase History</h2></a>';
            echo '<a href="index.php"><h2>Go Back</h2></a>';
        } else {
            echo "<h2>Error: " . mysqli_error($conn) . "</h2>";
        }
    }
?>