<?php
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
    function displayContent($result) {
        if (mysqli_num_rows($result) > 0) {
            $displayedItems = []; // Array to store displayed items
            echo '<div class="grid-container">';
            while ($row = mysqli_fetch_assoc($result)) {
                $key = $row['name'] . $row['brand'] . $row['category'] . $row['color'] . $row['price'] . $row['gender'] . $row['image_URL'] . $row['description']; // Define a key for merging

                if (!in_array($key, $displayedItems)) {
                    // If the item has not been displayed yet, display it
                    $displayedItems[] = $key;
                    
                echo "<div class='box-items'>";
                echo "<div class='image-container'>";
                echo '<a href="view.php?item_id=' . $row['id'] . '"><img src="'.$row['image_URL'].'" alt="'.$row["name"].'" /></a>';
                echo "</div>"; 
                echo "<div class='box-items-content'>";
                echo "<h3>" . $row["name"] . "</h3>";
                echo "<p class='price'>$" . $row["price"] . "</p>";
                echo "<a href='cart.php?item_id=" . $row['id'] . "' class='add-to-cart-btn'>Add to Cart</a>";
                echo "</div>";
                echo "</div>";                
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
