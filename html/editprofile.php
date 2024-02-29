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

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message"><?php echo $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); // Clear the error message from the session ?>
    <?php endif; ?>

    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index.html</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel='stylesheet' href='css/signupstyle.css' type='text/css' />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
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

    <div class="user-info">
    <?php
        if ($result) {
            $user = mysqli_fetch_assoc($result);
            echo '<form class="formstyle" action="edit.php" method="post">';
            echo '<label class="labelstyle" for="name">Name:</label>';
            echo '<input class="inputstyle2" type="text" name="name" id="name" value="' . $user['name'] . '" required tabindex="1"><br>';
            echo '<label class="labelstyle" for="username">Username:</label>';
            echo '<input class="inputstyle2" type="text" name="username" id="username" value="' . $user['username'] . '" required tabindex="2"><br>';
            echo '<label class="labelstyle" for="password">Password:</label>';
            echo '<input class="inputstyle2" type="password" name="password" id="password" value="' . $user['user_password'] . '" required tabindex="3"><br>';
            echo '<label class="labelstyle" for="confirmpassword">Confirm Password:</label>';
            echo '<input class="inputstyle2" type="password" name="confirmpassword" id="confirmpassword" value="' . $user['user_password'] . '" required tabindex="4"><br>';
            echo '<label class="labelstyle" for="balance">Balance:</label>';
            echo '<input class="inputstyle2" type="text" name="balance" id="balance" value="' . $user['balance'] . '" readonly tabindex="-1"><br>';
            echo '<button class="buttonstyle2" type="submit" value="save" tabindex="5">Save Edit</button>';
            echo '</form>';
        } else {
            echo "<h2>Error: " . mysqli_error($conn) . "</h2>";
        }
    }
        echo '<a class="astyle" href="user.php"><h3>Cancel</h3></a>';
    ?>
    </div>
</body>
</html>