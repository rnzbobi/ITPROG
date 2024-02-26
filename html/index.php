<?php
session_start();
include 'database.php';

if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    $username = $_SESSION['username'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index.html</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="Logo">
                </a>
            </div>
            <form class="search-form" action="index.php" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Search...">
                <button type="submit" class="search-button"><img src="images/search-interface-symbol.png" alt="Search"></button>
            </form>
            <div class="nav-links">
                <a href="social-media.html"><img src="images/social.png" alt="Social"></a>
                <a href="cart.html"><img src="images/shopping-cart.png" alt="Cart"></a>
                <a href="user.html"><img src="images/user.png" alt="User"></a>
                <?php
            if($loggedIn){
                echo '<a href="profile.php"><h2><span id="user-id">Profile</span></h2></a>';
                echo '<a href="logout.php"><h2><span id="user-id">Logout</span></h2></a>';
            } else {
                echo '<a href="login.php"><h2><span id="user-id">Login/Signup</span></h2></a>';
            }
            ?>
                <h2>Balance: <span id="balance-value">10000</span></h2>
            </div>
            <!-- <div class="Balance-header">
                <h2 id="balance">Balance: <span id="balance-value">10000</span></h2>
            </div> -->
        </div>
    </header>
    <aside class="sidebar">
                <form class="filter-form" action="index.php" method="GET">
                    <h2>Filters</h2>

                    <label for="category">Category:</label>
                    <select name="category" id="category">
                        <option value="">All</option>
                        <!-- Fetch and display unique categories from your database -->
                        <?php
                        $categories = getDistinctValues($conn, 'category', 'individual_clothes');
                        foreach ($categories as $category) {
                            echo '<option value="' . $category . '">' . $category . '</option>';
                        }
                        ?>
                    </select><br>
                    <label for="brand">Brand:</label>
                    <select name="brand" id="brand">
                        <option value="">All</option>
                        <!-- Fetch and display unique categories from your database -->
                        <?php
                        $brands = getDistinctValues($conn, 'brand', 'individual_clothes');
                        foreach ($brands as $brand) {
                            echo '<option value="' . $brand . '">' . $brand . '</option>';
                        }
                        ?>
                    </select><br>
                    <label for="color">Color:</label>
                    <select name="color" id="color">
                        <option value="">All</option>
                        <!-- Fetch and display unique categories from your database -->
                        <?php
                        $colors = getDistinctValues($conn, 'color', 'individual_clothes');
                        foreach ($colors as $color) {
                            echo '<option value="' . $color . '">' . $color . '</option>';
                        }
                        ?>
                    </select><br>
                    <label for="gender">Gender:</label>
                    <select name="gender" id="gender">
                        <option value="">All</option>
                        <!-- Fetch and display unique categories from your database -->
                        <?php
                        $genders = getDistinctValues($conn, 'gender', 'individual_clothes');
                        foreach ($genders as $gender) {
                            echo '<option value="' . $gender . '">' . $gender . '</option>';
                        }
                        ?>
                    </select><br>
                    <label for="size">Sizes:</label>
                    <select name="size" id="size">
                        <option value="">All</option>
                        <!-- Fetch and display unique categories from your database -->
                        <?php
                        $sizes = getDistinctValues($conn, 'size', 'individual_clothes');
                        foreach ($sizes as $size) {
                            echo '<option value="' . $size . '">' . $size . '</option>';
                        }
                        ?>
                    </select><br>
                    <label for="price">Price Range:</label>
                    <select name="price" id="price">
                        <option value="">All</option>
                        <?php
                        // Retrieve minimum and maximum prices from the database
                        $minPrice = executeQuery($conn, "SELECT MIN(price) AS min_price FROM individual_clothes")->fetch_assoc()['min_price'];
                        $maxPrice = executeQuery($conn, "SELECT MAX(price) AS max_price FROM individual_clothes")->fetch_assoc()['max_price'];

                        // Calculate the number of segments dynamically based on the range of prices
                        $segmentWidth = ($maxPrice - $minPrice) / 5; // Adjust the number of segments as needed
                        $startPrice = $minPrice;

                        // Define and generate price range segments dynamically
                        while ($startPrice < $maxPrice) {
                            $endPrice = $startPrice + $segmentWidth;
                            if ($endPrice >= $maxPrice) {
                                $optionLabel = '$' . $startPrice . ' - $' . $maxPrice;
                            } else {
                                $optionLabel = '$' . $startPrice . ' - $' . $endPrice;
                            }
                            echo '<option value="' . $startPrice . '-' . $endPrice . '">' . $optionLabel . '</option>';
                            $startPrice = $endPrice;
                        }
                        ?>
                    </select><br>
                    <button type="submit">Apply</button>
                </form>
                    </aside>
        <main>
        <form action="index.php" method="GET" id="sortForm" class="sort-form">
        <select name="sort" id="sort" class="sort-select">
            <option value="best-selling">Best Selling</option>
            <option value="A-Z">A - Z</option>
            <option value="Z-A">Z - A</option>
            <option value="lowhigh">Price low to high</option>
            <option value="highlow">Price high to low</option>
        </select>
    </form>

    <?php
    // Check if filter fields are set
    if (isset($_GET['category']) || isset($_GET['brand']) || isset($_GET['color']) || isset($_GET['gender']) || isset($_GET['size']) || isset($_GET['price'])) {
        // Include filter.php if filter fields are set
        include 'filter.php';
    } elseif (isset($_GET['search'])) {
        // Include search.php if search parameter is set
        include 'search.php';
    } elseif (isset($_GET['sort'])) {
        // Include sort.php if sort parameter is set
        include 'sort.php';
    } else {
        // Fetch default content if no filters, search, or sort
        $result = getDefaultContent($conn);
        // Display content based on the result fetched
        displayContent($result);
    }
    ?>

    <script>
        // Get the select element
        const sortSelect = document.getElementById('sort');

        // Add event listener for change event
        sortSelect.addEventListener('change', function() {
            // Submit the form when the select value changes
            document.getElementById('sortForm').submit();
        });
    </script>

    </main>
    <div class="clearfix"></div>
</body>
</html>
