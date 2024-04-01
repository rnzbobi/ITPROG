<?php

if (!function_exists('getComboOutfits')) {
    function getComboOutfits($conn) {
        $sql = "SELECT combo_id, item_id1, item_id2, item_id3, item_id4, combo_name, description, price
                FROM combo_clothes";
    
        $result = mysqli_query($conn, $sql);
        return $result;
    }
}

// Add this function to retrieve distinct values for a specific column
if (!function_exists('getDistinctValues')) {
    function getDistinctValues($conn, $column, $table) {
        $sql = "SELECT DISTINCT $column FROM $table";
        $result = mysqli_query($conn, $sql);
        $values = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $values[] = $row[$column];
        }

        return $values;
    }
}

if (!function_exists('getDefaultContent')) {
    function getDefaultContent($conn, $filters = []) {
        // Construct the SQL query for individual items
        $sql = "SELECT id, name, brand, category, color, price, gender, size, available_quantity, image_URL, description FROM individual_clothes";

        // Add WHERE conditions based on selected filters for individual items
        if (!empty($filters)) {
            $conditions = [];
            foreach ($filters as $column => $value) {
                if ($value !== '') {
                    if ($column === 'price') {
                        // Split the price range value into minimum and maximum prices
                        $priceRange = explode('-', $value);
                        $minPrice = $priceRange[0];
                        $maxPrice = $priceRange[1];
                        // Adjust the SQL condition to check if the price falls within the range for individual items
                        $conditions[] = "price BETWEEN $minPrice AND $maxPrice";
                    } else {
                        $conditions[] = "$column = '$value'";
                    }
                }
            }

            if (!empty($conditions)) {
                $sql .= ' WHERE ' . implode(' AND ', $conditions);
            }
        }

        // Construct the SQL query for combo items
        $comboSql = "SELECT combo_id, combo_name, 'Combo' AS brand, 'Combo' AS category, 'Combo' AS color, price, 'Unisex' AS gender, 'One Size' AS size, 1 AS available_quantity, image_URL, description FROM combo_clothes";

        // Add WHERE conditions based on selected filters for combo items
        if (!empty($filters)) {
            $comboConditions = [];
            foreach ($filters as $column => $value) {
                if ($value !== '') {
                    if ($column === 'price') {
                        // Split the price range value into minimum and maximum prices
                        $priceRange = explode('-', $value);
                        $minPrice = $priceRange[0];
                        $maxPrice = $priceRange[1];
                        // Adjust the SQL condition to check if the price falls within the range for combo items
                        $comboConditions[] = "price BETWEEN $minPrice AND $maxPrice";
                    }
                }
            }

            if (!empty($comboConditions)) {
                $comboSql .= ' WHERE ' . implode(' AND ', $comboConditions);
            }
        }

        // Union the individual items and combo items queries
        $sql .= " UNION " . $comboSql;

        $result = mysqli_query($conn, $sql);
        return $result;
    }
}



if (!function_exists('executeQuery')) {
    function executeQuery($conn, $sql) {
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
        return $result;
    }
}

if (!function_exists('displayContent')) {
    function displayContent($result, $conn) {
        if (mysqli_num_rows($result) > 0) {
            $displayedItems = []; // Array to store displayed items
            echo '<div class="grid-container">';

            // Display individual items and combo outfits
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['brand'] === 'Combo' || $row['category'] === 'Combo' || $row['color'] === 'Combo') { // Combo outfits
                    echo "<div class='box-items combo-outfit'>";
                    echo "<div class='image-container'>";
                    // Display images for the combo outfit
                    echo '<div class="combo-image-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; align-items: center;">';
                    echo '<a href="view_combo.php?item_id=' . $row['id'] . '"><img src="' . $row['image_URL'] . '" alt="' . $row["name"] . '" style="width: 300px; height: auto;" /></a>';
                    echo '</div>';
                    echo "</div>";
                    echo "<div class='box-items-content'>";
                    echo "<h3>" . $row["name"] . "</h3>";
                    echo "<p class='price'>$" . $row["price"] . "</p>";
                    echo '<p style="color:green;">COMBO ✓</p>';
                    echo "</div>";
                    echo "</div>";
                } else { // Individual items
                    // Check if the item has already been displayed or its available quantity is 0
                    $key = 'item_' . $row['name'] . $row['brand'] . $row['category'] . $row['color'] . $row['price'] . $row['gender'] . $row['description'];
                    if (!in_array($key, $displayedItems) && $row['available_quantity'] > 0) {
                        $displayedItems[] = $key; // Mark item as displayed
                        echo "<div class='box-items'>";
                        echo "<div class='image-container'>";
                        echo '<a href="view.php?item_id=' . $row['id'] . '"><img src="' . $row['image_URL'] . '" alt="' . $row["name"] . '" /></a>';
                        echo "</div>";
                        echo "<div class='box-items-content'>";
                        echo "<h3>" . $row["name"] . "</h3>";
                        echo "<p class='price'>$" . $row["price"] . "</p>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
            }

            echo '</div>';
        } else {
            echo "No results found.";
        }
    }
}







$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbclothes";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
