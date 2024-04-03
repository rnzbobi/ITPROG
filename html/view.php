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
    <title>View Item</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
        /* New styles for updated layout */
        .container {
            display: flex; /* Set up flex container for side-by-side layout */
            align-items: flex-start; /* Align items to the start of the flex container */
            gap: 2rem; /* Space between image and details */
            padding: 1rem;
        }
        .item-image {
            max-width: 700px; /* Maximum width of image container */
            flex: 5; /* Allows the image container to grow and shrink as needed */
        }
        .item-image img {
            width: 100%; /* Image will fill its container */
            height: auto; /* Maintain aspect ratio */
        }
        .item-info {
            font-family: 'Montserrat', sans-serif;
            flex: 2; /* The item-info section will take twice as much width as the image container */
            padding: 1rem; /* Padding around the text */
        }
        #bold{
            font-weight: 1000;
        }
        .item-details h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 3rem;
            margin-bottom: 0.5em;
        }
        .item-details .name {
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            color: #333;
            margin-bottom: 0.5em;
        }
		.item-details .color {
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            color: #333;
            margin-bottom: 0.5em;
        }
        .item-details .price {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.3rem;
            color: #E44D26;
            font-weight: bold;
        }
        .item-details label{
            font-family: 'Montserrat', sans-serif;
            font-size: 22px;
        }
        #size-select{
            font-size: 20px;
            padding: auto;
            width: 100px;
        }
        .size-options {
            font-family: 'Montserrat', sans-serif;
            margin: 20px 0;
        }
        .size-options button {
            font-family: 'Montserrat', sans-serif;
            padding: 5px 15px;
            margin-right: 10px;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            cursor: pointer;
        }
        .size-options button:hover {
            background-color: #e9e9e9;
        }
        .description {
            margin-top: 20px; /* Adds space above the description */
            padding: 15px; /* Adds padding inside the description box */
            background-color: #f2f2f2; /* Light grey background for the description area */
            border-radius: 8px; /* Rounded corners for the description box */
            font-size: 1rem; /* Sets the font size */
            line-height: 1.3; /* Sets the line height for better readability */
            color: #333; /* Dark grey color for the text, easier on the eyes than pure black */
            font-family: 'Montserrat', sans-serif;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* A subtle shadow to lift the description off the page */
        }
        .add-to-cart-btn, .checkout-btn {
            font-family: 'Montserrat', sans-serif;
            display: block; /* Makes the link fill the width of its container */
            padding: 25px 20px; /* Adds padding inside the button */
            background-color: #000000; /* Blue background color */
            color: white; /* White text */
            text-align: center; /* Centers the text inside the button */
            text-decoration: none; /* Removes the underline from the link */
            border-radius: 3px; /* Rounded corners for the button */
            border: none; /* No border */
            margin-top: 10px; /* Adds space above the first button */
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
            <form class="search-form" action="index.php" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Search...">
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
			 <h2>Balance: <span id="balance-value"><?php 
                    $getBalance = mysqli_query($conn,
                    "SELECT user_id.balance AS 'balanceUser'
                    FROM user_id 
                        LEFT JOIN carts ON user_id.userid = carts.user_id
                        LEFT JOIN individual_clothes ON carts.item_id = individual_clothes.id
                    WHERE user_id.username='$username'");
                if ($getBalance && mysqli_num_rows($getBalance) > 0){
                    $balanceRow = mysqli_fetch_assoc($getBalance);
                    $balance = $balanceRow['balanceUser'];
                    echo "$" . number_format($balance, 2); // Display balance with dollar sign and 2 decimal places
                }
                else{
                    echo "$" . number_format(0, 2); // Default to $0.00 if balance not found
                }
            ?></span></h2>
            </div>
        </div>
    </header> 
    <main>
        <div class="container">
            <?php
            if (isset($_GET['item_id'])) {
                $item_id = $_GET['item_id'];
                $sql = "SELECT * FROM individual_clothes WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $item_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    ?>
                    <div class="item-image">
                        <img src="<?php echo htmlspecialchars($row['image_URL']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    </div>
                    <div class="item-info">
                        <div class="item-details">
                            <h1><?php echo htmlspecialchars($row['brand']); ?></h1>
							 <p class="name"> <?php echo htmlspecialchars($row['name']); ?></p>
							 <p class="color"> <span id="bold">● Color:</span> <?php echo htmlspecialchars($row['color']); ?></p>
                             <p class="color"> <span id="bold">● Category:</span> <?php echo htmlspecialchars($row['category']); ?></p>
                             <p class="color"> <span id="bold">● Gender:</span> <?php echo htmlspecialchars($row['gender']); ?></p>
                             <p class="price">
                                $<?php echo htmlspecialchars($row['price']); ?>
                            </p>
                            <label for="size-select">Size:</label><br><br>
                            <select id="size-select" name="size" onchange="updateItemID(this.value);">
    <?php 
    
    $sizes_sql = "SELECT id, size FROM individual_clothes WHERE name = ? ORDER BY id";
    $sizes_stmt = $conn->prepare($sizes_sql);
    $sizes_stmt->bind_param("s", $row['name']);
    $sizes_stmt->execute();
    $sizes_result = $sizes_stmt->get_result();
    
    if ($sizes_result->num_rows > 0) {
        while ($size_row = $sizes_result->fetch_assoc()) {
            echo "<option value=\"" . htmlspecialchars($size_row['id']) . "\">" . htmlspecialchars($size_row['size']) . "</option>";
        }
    }
    $sizes_stmt->close();
    ?>
</select>
                            <a href="cart.php?item_id=<?php echo $item_id; ?>" class="add-to-cart-btn">Add to Cart</a>

                            <a href="checkoutitem.php?item_id=<?php echo $item_id; ?>" class="checkout-btn" onclick="return confirm('Are you sure you want to proceed to checkout?')">Checkout</a>
                            <div class="description">
                                <h3>Description:</h3><?php echo htmlspecialchars($row['description']); ?>
                             </div>      
                        </div>
                    </div>
                    <?php
                } else {
                    echo "Item not found.";
                }
                $stmt->close();
            } else {
                echo "Item ID not provided.";
            }
            ?>
        </div>
    </main>

    <script>
function updateItemID(selectedItemID) {
    var addToCartBtn = document.querySelector('.add-to-cart-btn');
    var checkoutBtn = document.querySelector('.checkout-btn');
    addToCartBtn.href = 'cart.php?item_id=' + selectedItemID;
    checkoutBtn.href = 'checkoutitem.php?item_id=' + selectedItemID;
}
</script>
</body>
</html>
