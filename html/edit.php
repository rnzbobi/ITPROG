<?php
session_start();

// Establish database connection
$conn = mysqli_connect("localhost", "root", "", "dbclothes") or die ("Unable to connect!". mysqli_connect_error());

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Retrieve user data from the database
$sql = "SELECT * FROM user_id WHERE username=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    $_SESSION['error'] = "Error fetching user data: " . mysqli_error($conn);
    header("Location: editprofile.php");
    exit();
}

$user = mysqli_fetch_assoc($result);
$id = $user['userid'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $new_username = $_POST["username"];
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirmpassword"]);

    if(strtolower($password) != strtolower($confirm_password)) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: editprofile.php");
        exit();
    } else {
        // Encryption key
        $encryption_key = "I7Pr063gGH3ad5";

        // Generate a random IV
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));

        // Encrypt the password with the generated IV
        $encrypted_password = openssl_encrypt($password, 'AES-256-CBC', $encryption_key, 0, $iv);

        // Combine IV and encrypted password
        $iv_encrypted_password = base64_encode($iv) . ":" . $encrypted_password;

        // Update user information including password
        $studQuery = "UPDATE user_id SET name=?, username=?, user_password=? WHERE userid=?";
        $stmt = mysqli_prepare($conn, $studQuery);
        mysqli_stmt_bind_param($stmt, "sssi", $name, $new_username, $iv_encrypted_password, $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION["username"] = $new_username;
            header("Location: user.php");
            exit();
        } else {
            $_SESSION['error'] = "Error editing user information: " . mysqli_error($conn);
            header("Location: editprofile.php");
            exit();
        }
    }
}

?>
