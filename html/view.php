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
        /* Your existing inline styles */

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
            font-size: 2rem;
            margin-bottom: 0.5em;
        }
        .item-details .name {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 0.5em;
        }
		.item-details .color {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 0.5em;
        }
        .item-details .price {
            font-size: 1.5rem;
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
                            <p class="price">
                                P<?php echo htmlspecialchars($row['price']); ?>
                            </p>
                            <div class="size-options">
                                <!-- Size options would be dynamically generated in production -->
                                <button>S</button>
                                <button>M</button>
                                <button>L</button>
                                <button>XL</button>
                            </div>
                            <a href="cart.php?item_id=<?php echo $item_id; ?>" class="add-to-cart-btn">Add to Cart</a>
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
