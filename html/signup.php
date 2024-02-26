<?php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $name = $_POST["name"];
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
    $sql = "SELECT * FROM user_id WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Username already exists, display an error message
        $error_message = "Username already exists, please choose a different one";
        echo "<h2>$error_message</h2>";
    } else {
        // Username is available, proceed with registration
        // Insert the new user into the database
        $sql = "INSERT INTO user_id (balance, name, username, user_password) VALUES (10000, '$name', '$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Registration successful, redirect to login page
            header("Location: login.php?registration_successful=true"); // Redirect to a welcome page or dashboard
            exit();
        } else {
            // Registration failed, display an error message
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
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
        <h1>SIGNUP</h1>

        <main>
            <form action="signup.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Signup</button>
            </form>

            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </main>
    </div>
</body>
</html>