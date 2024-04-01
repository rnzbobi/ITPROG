<?php
include 'database.php';

if (isset($_GET['search'])) {
    $searchKeyword = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Query for individual items
    $sql = "SELECT id, name, brand, category, color, price, gender, size, available_quantity, image_URL, description 
            FROM individual_clothes 
            WHERE name LIKE '%$searchKeyword%'";

    // Query for combo items
    $comboSql = "SELECT combo_id, combo_name AS name, 'Combo' AS brand, 'Combo' AS category, 'Combo' AS color, price, 'Unisex' AS gender, 'One Size' AS size, available_quantity, image_URL, description 
                 FROM combo_clothes 
                 WHERE combo_name LIKE '%$searchKeyword%'";

    // Union the individual items and combo items queries
    $sql .= " UNION " . $comboSql;

    $result = executeQuery($conn, $sql);
    displayContent($result, $conn);
} else {
    echo "No search keyword provided.";
}
?>