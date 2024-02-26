<?php
    session_start();

    $conn = mysqli_connect("localhost", "root", "") or die ("Unable to connect!". mysqli_error());
    mysqli_select_db($conn, "test");
    
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
        echo "<h2>Name: ". $user['name'] . "</h2><br>";
        echo "<h2>Username: ". $user['username'] . "</h2><br>";
        $masked_password = str_repeat('*', strlen($user['user_password']));
        echo "<h2>Password: " . $masked_password . "</h2><br>";
        echo "<h2>Balance: ". $user['balance'] . "</h2><br>";
        echo '<a href="index.php"><h2>Go Back</h2></a>';
    } else {
        echo "<h2>Error: " . mysqli_error($conn) . "</h2>";
    }
?>