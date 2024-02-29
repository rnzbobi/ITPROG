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
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }
        .item-details-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            gap: 20px;
        }
        .item-image img {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .item-info {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 300px;
        }
        .item-info h2 {
            margin-top: 0;
        }
        .add-to-cart-btn, .go-back-btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .add-to-cart-btn:hover, .go-back-btn:hover {
			background: #0056b3; /* Slightly darker blue on hover for both */
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
        <div class="item-details-container">
            <?php
            // Check if item_id is provided in the URL parameters
            if(isset($_GET['item_id'])) {
                $item_id = $_GET['item_id'];
                
                // Query to retrieve item details from the individual_clothes table
                $sql = "SELECT * FROM individual_clothes WHERE id = $item_id";
                $result = executeQuery($conn, $sql);
                
                // Check if the query returned any result
                if(mysqli_num_rows($result) > 0) {
                    // Fetch the item details
                    $row = mysqli_fetch_assoc($result);
                    $name = $row['name'];
                    $brand = $row['brand'];
                    $category = $row['category'];
                    $color = $row['color'];
                    $price = $row['price'];
                    $gender = $row['gender'];
                    $size = $row['size'];
                    $available_quantity = $row['available_quantity'];
                    $image_URL = $row['image_URL'];
                    $description = $row['description'];
                    
                    // Display the item image
					echo '<div class="item-image">';
					echo '<img src="' . $image_URL . '" alt="' . $name . '" style="max-width: 300px; max-height: 300px;">';
					echo '</div>';
                    
                    // Display the item details
                    echo '<div class="item-info">';
                    echo '<h2>' . $name . '</h2>';
                    echo '<p><strong>Brand:</strong> ' . $brand . '</p>';
                    echo '<p><strong>Category:</strong> ' . $category . '</p>';
                    echo '<p><strong>Color:</strong> ' . $color . '</p>';
                    echo '<p><strong>Price:</strong> $' . $price . '</p>';
                    echo '<p><strong>Gender:</strong> ' . $gender . '</p>';
                    echo '<p><strong>Size:</strong> ' . $size . '</p>';
                    echo '<p><strong>Available Quantity:</strong> ' . $available_quantity . '</p>';
                    echo '<p><strong>Description:</strong> ' . $description . '</p>';
                    // Add any other information you want to display
                    echo '<a href="cart.php?item_id=' . $item_id . '" class="add-to-cart-btn">Add to Cart</a>';
					echo '<a href="index.php" class="go-back-btn">Go Back</a>';
                    echo '</div>';
					
                } else {
                    echo "Item not found.";
                }
            } else {
                echo "Item ID not provided.";
            }
            ?>
        </div>
    </main>
</body>
</html>
