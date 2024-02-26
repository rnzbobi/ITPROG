<?php
include 'database.php';

// Check if any filter parameters are present in the URL
if (isset($_GET['category']) || isset($_GET['brand']) || isset($_GET['color']) || isset($_GET['gender']) || isset($_GET['size']) || isset($_GET['price']))  {
    // Fetch filtered content if filters are applied
    $filters = array(
        'category' => $_GET['category'],
        'brand' => $_GET['brand'],
        'color' => $_GET['color'],
        'gender' => $_GET['gender'],
        'size' => $_GET['size'],
        'price' => $_GET['price'],
        // Remove 'price' from here since it's not a direct filter column
        // Add other filters here like price, color, gender, size
    );

    // Construct the SQL query based on the applied filters
    $sql = "SELECT id, name, brand, category, color, price, gender, size, available_quantity, image_URL, description FROM individual_clothes WHERE 1";

    foreach ($filters as $column => $value) {
        if (!empty($value)) {
            // Adjust the handling for the 'price' filter
            if ($column === 'price') {
                // Split the price range value into minimum and maximum prices
                $priceRange = explode('-', $value);
                $minPrice = $priceRange[0];
                $maxPrice = $priceRange[1];
                // Adjust the SQL condition to check if the price falls within the range
                $sql .= " AND price BETWEEN $minPrice AND $maxPrice";
            } else {
                // For other filters, add conditions as before
                $sql .= " AND $column = '$value'";
            }
        }
    }

    $result = executeQuery($conn, $sql);

    // Display filtered content
    displayContent($result);
} else {
    echo "No filters applied.";
}
?>
