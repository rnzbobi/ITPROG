<?php
include 'database.php';

if (isset($_GET['search'])) {
    $searchKeyword = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT id, name, brand, category, color, price, gender, size, available_quantity, image_URL, description 
            FROM individual_clothes 
            WHERE name LIKE '%$searchKeyword%'";
    $result = executeQuery($conn, $sql);
    displayContent($result);
} else {
    echo "No search keyword provided.";
}
?>
