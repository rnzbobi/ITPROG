<?php
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Assume database connection is successful from here onwards
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Mode</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel='stylesheet' href='css/signupstyle.css' type='text/css' />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body class="bodystyle">

<header>
    <!-- Consider reusing the header content from user.php -->
</header>

<div class="user-info">
    <h1>Seller Mode</h1>
    <a class="astyle" href="additem.php"><h3>Add Item</h3></a><br><br>
    <a class="astyle" href="deleteitem.php"><h3>Delete Item</h3></a><br><br>
    <a class="astyle" href="edititem.php"><h3>Edit Item</h3></a><br><br>
    <a class="astyle" href="index.php"><h3>Go Back</h3></a>
</div>

</body>
</html>