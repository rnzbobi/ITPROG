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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        .confirm.custom-black-btn {
            background-color: black; /* Set the background color to black */
            color: white; /* Set the text color to white */
        }
    </style>
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="Logo">
                </a>
            </div>
            <form class="search-form" action="index.php?search=" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="search-button"><img src="images/search-interface-symbol.png" alt="Search"></button>
            </form>
            <div class="nav-links">
                <a href="social-media.php"><img src="images/social.png" alt="Social"></a>
                <a href="view_cart.php"><img src="images/shopping-cart.png" alt="Cart"></a>
                <a href="user.php"><img src="images/user.png" alt="User"></a>
                <?php
            if($loggedIn){
                echo '<a href="user.php"><h2><span id="user-id">Profile</span></h2></a>';
                echo '<a href="logout.php"><h2><span id="user-id">Logout</span></h2></a>';
            } else {
                echo '<a href="login.php"><h2><span id="user-id">Login/Signup</span></h2></a>';
            }
            ?>
                <h2>Balance: <span id="balance-value">10000</span></h2>
            </div>
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
                        // Check if the current category matches the selected category
                        $selected = isset($_GET['category']) && $_GET['category'] === $category ? 'selected' : '';
                        echo '<option value="' . $category . '" ' . $selected . '>' . $category . '</option>';
                    }
                    ?>
                </select><br>
                
                <label for="brand">Brand:</label>
                <select name="brand" id="brand">
                    <option value="">All</option>
                    <!-- Fetch and display unique brands from your database -->
                    <?php
                    $brands = getDistinctValues($conn, 'brand', 'individual_clothes');
                    foreach ($brands as $brand) {
                        // Check if the current brand matches the selected brand
                        $selected = isset($_GET['brand']) && $_GET['brand'] === $brand ? 'selected' : '';
                        echo '<option value="' . $brand . '" ' . $selected . '>' . $brand . '</option>';
                    }
                    ?>
                </select><br>
                
                <label for="color">Color:</label>
                <select name="color" id="color">
                    <option value="">All</option>
                    <!-- Fetch and display unique colors from your database -->
                    <?php
                    $colors = getDistinctValues($conn, 'color', 'individual_clothes');
                    foreach ($colors as $color) {
                        // Check if the current color matches the selected color
                        $selected = isset($_GET['color']) && $_GET['color'] === $color ? 'selected' : '';
                        echo '<option value="' . $color . '" ' . $selected . '>' . $color . '</option>';
                    }
                    ?>
                </select><br>
                
                <label for="gender">Gender:</label>
                <select name="gender" id="gender">
                    <option value="">All</option>
                    <!-- Fetch and display unique genders from your database -->
                    <?php
                    $genders = getDistinctValues($conn, 'gender', 'individual_clothes');
                    foreach ($genders as $gender) {
                        // Check if the current gender matches the selected gender
                        $selected = isset($_GET['gender']) && $_GET['gender'] === $gender ? 'selected' : '';
                        echo '<option value="' . $gender . '" ' . $selected . '>' . $gender . '</option>';
                    }
                    ?>
                </select><br>
                
                <label for="size">Sizes:</label>
                <select name="size" id="size">
                    <option value="">All</option>
                    <!-- Fetch and display unique sizes from your database -->
                    <?php
                    $sizes = getDistinctValues($conn, 'size', 'individual_clothes');
                    foreach ($sizes as $size) {
                        // Check if the current size matches the selected size
                        $selected = isset($_GET['size']) && $_GET['size'] === $size ? 'selected' : '';
                        echo '<option value="' . $size . '" ' . $selected . '>' . $size . '</option>';
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
                        // Check if the current price range matches the selected price range
                        $selected = isset($_GET['price']) && $_GET['price'] === ($startPrice . '-' . $endPrice) ? 'selected' : '';
                        echo '<option value="' . $startPrice . '-' . $endPrice . '" ' . $selected . '>' . $optionLabel . '</option>';
                        $startPrice = $endPrice;
                    }
                    ?>
                </select><br>
                
                <!-- Include the hidden input field for sorting option -->
                <input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? $_GET['sort'] : ''; ?>">
            </form>
        </aside>
        <main>
        <form action="index.php" method="GET" id="sortForm" class="sort-form">
        <select name="sort" id="sort" class="sort-select">
            <option value="">Sort by:</option>
            <option value="best-selling"<?php if (isset($_GET['sort']) && $_GET['sort'] === 'best-selling') echo ' selected'; ?>>Best Selling</option>
            <option value="A-Z"<?php if (isset($_GET['sort']) && $_GET['sort'] === 'A-Z') echo ' selected'; ?>>A - Z</option>
            <option value="Z-A"<?php if (isset($_GET['sort']) && $_GET['sort'] === 'Z-A') echo ' selected'; ?>>Z - A</option>
            <option value="lowhigh"<?php if (isset($_GET['sort']) && $_GET['sort'] === 'lowhigh') echo ' selected'; ?>>Price low to high</option>
            <option value="highlow"<?php if (isset($_GET['sort']) && $_GET['sort'] === 'highlow') echo ' selected'; ?>>Price high to low</option>
            <input type="hidden" name="category" value="<?php echo isset($_GET['category']) ? $_GET['category'] : ''; ?>">
            <input type="hidden" name="brand" value="<?php echo isset($_GET['brand']) ? $_GET['brand'] : ''; ?>">
            <input type="hidden" name="color" value="<?php echo isset($_GET['color']) ? $_GET['color'] : ''; ?>">
            <input type="hidden" name="gender" value="<?php echo isset($_GET['gender']) ? $_GET['gender'] : ''; ?>">
            <input type="hidden" name="size" value="<?php echo isset($_GET['size']) ? $_GET['size'] : ''; ?>">
            <input type="hidden" name="price" value="<?php echo isset($_GET['price']) ? $_GET['price'] : ''; ?>">
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
		
		
        if (isset($_SESSION['item_added_to_cart'])) {
            unset($_SESSION['item_added_to_cart']);
            echo '<script>
            Swal.fire({
                title: "Success!",
                text: "Item added to cart",
                icon: "success",
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "swal2-confirm custom-black-btn" // Apply the custom class
                }
            });
            </script>';
        }
        ?>

    <script>
    // Get all select elements in the filter form
    const filterSelects = document.querySelectorAll('.filter-form select');

    // Function to handle filter form submission
    const handleFilterFormSubmission = () => {
        // Append the filter parameters to the form action URL
        const filterForm = document.querySelector('.filter-form');
        const currentUrl = window.location.href.split('?')[0];

        // Get the filter parameters from the filter form
        const filterParams = new URLSearchParams(new FormData(filterForm));

        // Get the sort parameter from the URL
        const sortParam = new URLSearchParams(window.location.search).get('sort');

        // Construct the final form action URL with both sorting and filter parameters
        const finalUrl = currentUrl + '?' + filterParams.toString() + (sortParam ? '&sort=' + sortParam : '');

        // Set the final URL to the action attribute of the filter form
        filterForm.action = finalUrl;

        // Submit the filter form
        filterForm.submit();
    };

    // Add event listeners to all filter select elements
    filterSelects.forEach(select => {
        select.addEventListener('change', handleFilterFormSubmission);
    });

    // Get the select element for sorting
    const sortSelect = document.getElementById('sort');

    // Add event listener for change event
    sortSelect.addEventListener('change', function() {
        // Append the sorting option to the form action URL
        const sortForm = document.getElementById('sortForm');
        const currentUrl = window.location.href.split('?')[0];
        const sortValue = sortSelect.value;
        const sortParam = sortValue ? '&sort=' + sortValue : '';

        // Get the filter parameters from the filter form
        const filterParams = new URLSearchParams(new FormData(document.querySelector('.filter-form')));

        // Construct the final form action URL with both sorting and filter parameters
        const finalUrl = currentUrl + '?' + filterParams.toString() + sortParam;

        // Set the final URL to the action attribute of the sort form
        sortForm.action = finalUrl;

        // Submit the sort form
        sortForm.submit();
    });
    </script>
    </main>
    <div class="clearfix"></div>

</body>
</html>
