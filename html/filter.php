<?php
include 'database.php';

// Check if any filter parameters are present in the URL
if (isset($_GET['category']) || isset($_GET['brand']) || isset($_GET['color']) || isset($_GET['gender']) || isset($_GET['size']) || isset($_GET['price'])) {
    // Fetch filtered content if filters are applied
    $filters = array(
        'category' => $_GET['category'],
        'brand' => $_GET['brand'],
        'color' => $_GET['color'],
        'gender' => $_GET['gender'],
        'size' => $_GET['size'],
        'price' => $_GET['price'],
    );

    // Construct the SQL query for individual items based on the applied filters
    $sql = "SELECT id, name, brand, category, color, price, gender, size, available_quantity, image_URL, description FROM individual_clothes WHERE 1";

    foreach ($filters as $column => $value) {
        if (!empty($value)) {
            if ($column === 'price') {
                // Split the price range value into minimum and maximum prices
                $priceRange = explode('-', $value);
                $minPrice = $priceRange[0];
                $maxPrice = $priceRange[1];
                // Adjust the SQL condition to check if the price falls within the range for individual items
                $sql .= " AND price BETWEEN $minPrice AND $maxPrice";
            } else {
                // For other filters, add conditions for individual items
                $sql .= " AND $column = '$value'";
            }
        }
    }

    // Construct the SQL query for combo items based on the applied filters
    $comboSql = "SELECT combo_id, combo_name AS name, 'Combo' AS brand, 'Combo' AS category, 'Combo' AS color, price, 'Unisex' AS gender, 'One Size' AS size, 1 AS available_quantity,image_URL, description FROM combo_clothes WHERE 1";

    foreach ($filters as $column => $value) {
        if (!empty($value)) {
            if ($column === 'price') {
                // Split the price range value into minimum and maximum prices
                $priceRange = explode('-', $value);
                $minPrice = $priceRange[0];
                $maxPrice = $priceRange[1];
                // Adjust the SQL condition to check if the price falls within the range for combo items
                $comboSql .= " AND price BETWEEN $minPrice AND $maxPrice";
            }
        }
    }

    // Union the individual items and combo items queries
    $sql .= " UNION " . $comboSql;

    // Check if sorting option is set
    if (isset($_GET['sort'])) {
        $sortOption = $_GET['sort'];

        // Initialize the sorting query based on the selected option
        switch ($sortOption) {
            case 'best-selling':
                // Add sorting query for best-selling items (not implemented)
                break;
            case 'A-Z':
                // Add sorting query for A-Z
                $sql .= ' ORDER BY name ASC';
                break;
            case 'Z-A':
                // Add sorting query for Z-A
                $sql .= ' ORDER BY name DESC';
                break;
            case 'lowhigh':
                // Add sorting query for Price low to high
                $sql .= ' ORDER BY price ASC';
                break;
            case 'highlow':
                // Add sorting query for Price high to low
                $sql .= ' ORDER BY price DESC';
                break;
            default:
                // Default sorting query
                break;
        }
    }

    $result = executeQuery($conn, $sql);

    // Display filtered and sorted content
    displayContent($result, $conn);
} else {
    echo "No filters applied.";
}
?>
