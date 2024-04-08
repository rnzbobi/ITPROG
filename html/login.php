<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Encryption key
    $encryption_key = "I7Pr063gGH3ad5";

    // Create connection
    $conn = mysqli_connect("localhost", "root", "") or die ("Unable to connect!". mysqli_error());
    mysqli_select_db($conn, "dbclothes");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve IV and encrypted password from the database based on username
    $sql = "SELECT user_password FROM user_id WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Username exists, verify the password
        $row = $result->fetch_assoc();
        $iv_encrypted_password = $row["user_password"];

        // Separate IV and encrypted password
        list($iv, $encrypted_password) = explode(":", $iv_encrypted_password);

        // Decrypt the stored password
        $decrypted_password = openssl_decrypt($encrypted_password, "AES-256-CBC", $encryption_key, 0, base64_decode($iv));

        // Verify the password
        if ($decrypted_password === $password) {
            // Password is correct
            $_SESSION["username"] = $username;
            header("Location: index.php"); // Redirect to a welcome page or dashboard
            exit();
        } else {
            // Password is incorrect
            echo "<h1>LOGIN FAILED! Username or password does not match!</h1>";
        }
    } else {
        // Username doesn't exist
        echo "<h1>LOGIN FAILED! Username or password does not match!</h1>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/signupstyle.css" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
</head>

<body class="bodystyle">
    <div class="container">
        <h1 class="h1style">LOGIN</h1>

        <main>
            <form class="formstyle" action="login.php" method="post">
                
                <label class="labelstyle" for="username">Username:</label>
                <input class="inputstyle" type="text" id="username" name="username" required>

                <label class="labelstyle" for="password">Password:</label>
                <input class="inputstyle" type="password" id="password" name="password" required>

                <button class="buttonstyle" type="submit">Login</button>
            </form>

            <p class="pstyle">Don't have an account? <a class="astyle" href="signup.php"> Signup here</a>.</p>
        </main>
    </div>
</body>
</html>
