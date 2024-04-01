<?php
// Include database.php to access database functions and establish database connection
include 'database.php';

// Check if the sorting option is set in the URL
if (isset($_GET['sort'])) {
    // Get the selected sorting option
    $sortOption = $_GET['sort'];

    // Initialize the sorting query for individual items based on the selected option
    $sortQuery = '';

    switch ($sortOption) {
        case 'best-selling':
            // Add sorting query for best-selling items (not implemented)
            break;
        case 'A-Z':
            // Add sorting query for A-Z
            $sortQuery = 'ORDER BY name ASC';
            break;
        case 'Z-A':
            // Add sorting query for Z-A
            $sortQuery = 'ORDER BY name DESC';
            break;
        case 'lowhigh':
            // Add sorting query for Price low to high
            $sortQuery = 'ORDER BY price ASC';
            break;
        case 'highlow':
            // Add sorting query for Price high to low
            $sortQuery = 'ORDER BY price DESC';
            break;
        default:
            // Default sorting query
            $sortQuery = '';
            break;
    }

    // Construct the SQL query for individual items with sorting
    $sql = "SELECT id, name, brand, category, color, price, gender, size, available_quantity, image_URL, description FROM individual_clothes $sortQuery";

    // Construct the SQL query for combo items with sorting
    $comboSql = "SELECT combo_id, combo_name AS name, 'Combo' AS brand, 'Combo' AS category, 'Combo' AS color, price, 'Unisex' AS gender, 'One Size' AS size, 1 AS available_quantity, image_URL, description FROM combo_clothes $sortQuery";

    // Union the individual items and combo items queries
    $sql .= " UNION " . $comboSql;

    $result = executeQuery($conn, $sql);
    displayContent($result, $conn);
} else {
    // If the sort option is not set, handle it accordingly
    echo 'Sort option not provided.';
}
?>