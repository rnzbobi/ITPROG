<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Perform authentication (you should replace this with your actual authentication logic)
    // For example, checking against a database
    // Get username and pass from database
    $validUsername = "your_username"; // Replace with your actual username
    $validPassword = "your_password"; // Replace with your actual password

    if ($username == $validUsername && $password == $validPassword) {
        // Authentication successful
        $_SESSION["username"] = $username;
        header("Location: index.php"); // Redirect to a welcome page or dashboard
        exit();
    } else {
        // Authentication failed
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index.html</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
</head>

<body>
    <h1>LOGIN</h1>

    <main>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>