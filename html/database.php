<?php

if (!function_exists('getComboOutfits')) {
    function getComboOutfits($conn) {
        $sql = "SELECT c.combo_id, c.combo_name, c.description, c.price, c.item_id1, c.item_id2, c.item_id3, c.item_id4,
                       i1.image_URL AS image_URL1, i2.image_URL AS image_URL2, i3.image_URL AS image_URL3, i4.image_URL AS image_URL4
                FROM combo_clothes c
                LEFT JOIN individual_clothes i1 ON c.item_id1 = i1.id
                LEFT JOIN individual_clothes i2 ON c.item_id2 = i2.id
                LEFT JOIN individual_clothes i3 ON c.item_id3 = i3.id
                LEFT JOIN individual_clothes i4 ON c.item_id4 = i4.id
                WHERE (c.item_id1 IS NOT NULL OR c.item_id2 IS NOT NULL OR c.item_id3 IS NOT NULL OR c.item_id4 IS NOT NULL)";

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
        $sql = "SELECT id, name, brand, category, color, price, gender, size, available_quantity, image_URL, description FROM individual_clothes";

        // Add WHERE conditions based on selected filters
        if (!empty($filters)) {
            $conditions = [];
            foreach ($filters as $column => $value) {
                if ($value !== '') {
                    $conditions[] = "$column = '$value'";
                }
            }

            if (!empty($conditions)) {
                $sql .= ' WHERE ' . implode(' AND ', $conditions);
            }
        }

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
            while ($row = mysqli_fetch_assoc($result)) {
                $key = $row['name'] . $row['brand'] . $row['category'] . $row['color'] . $row['price'] . $row['gender'] . $row['image_URL'] . $row['description']; // Define a key for merging

                if (!in_array($key, $displayedItems) && $row['available_quantity'] > 0) {
                    // If the item has not been displayed yet and available_quantity is greater than 0, display it
                    $displayedItems[] = $key;

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

            // Display combo outfits
            $comboOutfits = getComboOutfits($conn);
            while ($comboOutfit = mysqli_fetch_assoc($comboOutfits)) {
                echo "<div class='box-items combo-outfit'>";
                echo "<div class='image-container'>";

                // Display images for the combo outfit
                echo '<div class="combo-image-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; align-items: center;">';
                if (!empty($comboOutfit['image_URL1'])) {
                    echo '<img src="' . $comboOutfit['image_URL1'] . '" alt="Combo Outfit" class="combo-image" style="max-width: 100%; height: auto;" />';
                }
                if (!empty($comboOutfit['image_URL2'])) {
                    echo '<img src="' . $comboOutfit['image_URL2'] . '" alt="Combo Outfit" class="combo-image" style="max-width: 100%; height: auto;" />';
                }
                if (!empty($comboOutfit['image_URL3'])) {
                    echo '<img src="' . $comboOutfit['image_URL3'] . '" alt="Combo Outfit" class="combo-image" style="max-width: 100%; height: auto;" />';
                }
                if (!empty($comboOutfit['image_URL4'])) {
                    echo '<img src="' . $comboOutfit['image_URL4'] . '" alt="Combo Outfit" class="combo-image" style="max-width: 100%; height: auto;" />';
                }
                echo '</div>';




                echo "</div>";
                echo "<div class='box-items-content'>";
                echo "<h3>" . $comboOutfit["combo_name"] . "</h3>";
                echo "<p class='price'>$" . $comboOutfit["price"] . "</p>";
                echo '<p style="color:green;">COMBO ✓</p>';
                echo "</div>";
                echo "</div>";
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
