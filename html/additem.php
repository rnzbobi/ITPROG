<?php
session_start();
include 'database.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO individual_clothes (name, brand, category, color, gender, size, price, available_quantity, image_url, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Set parameters
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $color = $_POST['color'];
    $gender = $_POST['gender'];
    $size = $_POST['size'];
    $price = (double)$_POST['price']; 
    $quantity = (int)$_POST['quantity'];
    $image_url = $_POST['image_url'];
    $description = $_POST['description'];

    // Bind parameters with correct types
    $stmt->bind_param("ssssssdiss", $name, $brand, $category, $color, $gender, $size, $price, $quantity, $image_url, $description);

    // Execute
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <style>
        .form-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .item-form {
            display: grid;
            grid-gap: 1rem;
            padding: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            padding: 0.5rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group textarea {
            height: 120px;
            resize: vertical;
        }

        .submit-btn {
            padding: 1rem;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<header>
    <!-- Your header content -->
</header>

<main>
    <div class="form-container">
        <h1>Add New Item</h1>
        <form method="POST" class="item-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" required>
            </div>

            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <input type="text" id="gender" name="gender" required>
            </div>

            <div class="form-group">
                <label for="size">Size:</label>
                <input type="text" id="size" name="size" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required>
            </div>

            <div class="form-group">
                <label for="quantity">Available Quantity:</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>

            <div class="form-group">
                <label for="image_url">Image URL:</label>
                <input type="text" id="image_url" name="image_url" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <input type="submit" value="Add Item" class="submit-btn">
            </div>
        </form>
    </div>
</main>


<footer>
    <!-- Your footer content -->
</footer>

</body>
</html>
