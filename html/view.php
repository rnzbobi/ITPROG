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
    <!-- Your other existing links and styles -->
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
            flex: 2; /* The item-info section will take twice as much width as the image container */
            padding: 1rem; /* Padding around the text */
        }
        .item-details h1 {
            font-size: 3rem;
            margin-bottom: 0.5em;
        }
        .item-details .name {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 0.5em;
        }
		.item-details .color {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 0.5em;
        }
        .item-details .price {
            font-size: 2.3rem;
            color: #E44D26;
            font-weight: bold;
        }
        .size-options {
            margin: 20px 0;
        }
        .size-options button {
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* A subtle shadow to lift the description off the page */
        }
        .add-to-cart-btn, .checkout-btn {
            display: block; /* Makes the link fill the width of its container */
            padding: 10px 15px; /* Adds padding inside the button */
            background-color: #000000; /* Blue background color */
            color: white; /* White text */
            text-align: center; /* Centers the text inside the button */
            text-decoration: none; /* Removes the underline from the link */
            border-radius: 5px; /* Rounded corners for the button */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Adds a shadow for depth */
            border: none; /* No border */
            margin-top: 10px; /* Adds space above the first button */
        }
        .checkout-btn {
           background-color: #28a745; /* Green background color for the checkout button */
            margin-top: 20px; /* Adds more space above the checkout button */
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
                <a href="social-media.html"><img src="images/social.png" alt="Social"></a>
                <a href="user.html"><img src="images/user.png" alt="User"></a>
                <a href="cart.html"><img src="images/shopping-cart.png" alt="Cart"></a>
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
            <div class="Balance-header">
                <h2 id="balance">Balance: <span id="balance-value">10000</span></h2>
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
							 <p class="color"> <?php echo htmlspecialchars($row['color']); ?></p>
                             <p class="color"> <?php echo htmlspecialchars($row['category']); ?></p>
                             <p class="color"> <?php echo htmlspecialchars($row['gender']); ?></p>
                             <p class="price">
                                P<?php echo htmlspecialchars($row['price']); ?>
                            </p>
                            <label for="size-select">Size:</label>
                            <select id="size-select" name="size">
                                <?php 
                                // Assuming sizes are stored in a comma-separated list in the database
                                $sizes = explode(',', $row['size']); // Convert the string to an array
                                foreach ($sizes as $size) {
                                    echo "<option value=\"$size\">$size</option>";
                                }
                                ?>
                            </select>
                            <a href="cart.php?item_id=<?php echo $item_id; ?>" class="add-to-cart-btn">Add to Cart</a>

                            <a href="checkout.php?item_id=<?php echo $item_id; ?>" class="add-to-cart-btn">Checkout</a>
                            <div class="description">
                                <?php echo htmlspecialchars($row['description']); ?>
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

    <!-- Your existing footer -->
</body>
</html>
