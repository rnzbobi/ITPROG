<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Create connection
    $conn = mysqli_connect("localhost", "root", "") or die ("Unable to connect!". mysqli_error());
        mysqli_select_db($conn, "dbclothes");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username already exists in the database
    $sql = "SELECT * FROM user_id WHERE username='$username' AND user_password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Username exists
        $_SESSION["username"] = $username;
        header("Location: index.php"); // Redirect to a welcome page or dashboard
        exit();
    } else {      
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
            echo "<h1>LOGIN FAILED! Username or password does not match!</h1>";
    }
}
?>

<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="css/signupstyle.css" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>LOGIN</h1>

        <main>
            <form action="login.php" method="post">
                
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>

            <p>Don't have an account? <a href="signup.php"> Signup here</a>.</p>
        </main>
    </div>
</body>
</html>