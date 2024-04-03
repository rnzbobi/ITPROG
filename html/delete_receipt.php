<?php
session_start();
include 'database.php';

$studQuery = "DELETE FROM receipt";
$stmt = mysqli_prepare($conn, $studQuery);
    
mysqli_stmt_execute($stmt);

header("Location: index.php");
exit();
?>