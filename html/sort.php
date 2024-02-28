<?php
// Include database.php to access database functions and establish database connection
include 'database.php';

// Check if the sorting option is set in the URL
if (isset($_GET['sort'])) {
    // Get the selected sorting option
    $sortOption = $_GET['sort'];

    // Initialize the sorting query based on the selected option
    $sortQuery = '';

    switch ($sortOption) {
        case 'best-selling':
            // Add sorting query for best-selling items
            $sortQuery = 'ORDER BY sold_quantity DESC';
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

    // Construct the SQL query to fetch sorted content
    $sql = "SELECT id, name, brand, category, color, price, gender, size, available_quantity, image_URL, description FROM individual_clothes $sortQuery";

    // Execute the SQL query
    $result = executeQuery($conn, $sql);

    // Display the sorted content
    displayContent($result);
} else {
    // If the sort option is not set, handle it accordingly
    echo 'Sort option not provided.';
}
?>
