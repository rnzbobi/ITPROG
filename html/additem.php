<?php
session_start(); // Start the session at the beginning
include 'database.php';


// Function to create the uploads directory if it doesn't exist
function createUploadsDirectory($dir) {
    if (!file_exists($dir)) {
        if (!mkdir($dir, 0777, true)) {
            die('Failed to create uploads directory.');
        }
    }
}

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
    $total_quantity = $quantity; 

    // Check if a file was uploaded
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imageFile'];
        $uploadDir = 'uploads/';

        // Create the uploads directory if it doesn't exist
        createUploadsDirectory($uploadDir);

        // Generate a unique file name
        $fileDestination = $uploadDir . uniqid() . '_' . basename($file['name']);

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($file['tmp_name'], $fileDestination)) {
            $image_url = $fileDestination;
        } else {
            $image_url = '';
        }
    }


    // Check if an item with the same details already exists
    $checkStmt = $conn->prepare("SELECT * FROM individual_clothes WHERE name = ? AND brand = ? AND category = ? AND color = ? AND gender = ? AND size = ? AND price = ? AND available_quantity = ? AND image_url = ? AND description = ?");
    $checkStmt->bind_param("ssssssdiss", $name, $brand, $category, $color, $gender, $size, $price, $quantity, $image_url, $description);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    if ($result->num_rows > 0) {
        // Item already exists
        $_SESSION['item_exists'] = true; // Set a flag for item existence
        header("Location: addItem.php"); // Redirect to the same page
        exit();
    } else {

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO individual_clothes (name, brand, category, color, gender, size, price, available_quantity, image_url, description, total_quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
    // Bind parameters with correct types
    $stmt->bind_param("ssssssdissi", $name, $brand, $category, $color, $gender, $size, $price, $quantity, $image_url, $description, $total_quantity);

    // Execute
    if ($stmt->execute()) {
        $_SESSION['item_added'] = true; // Use a flag to indicate item addition
        header("Location: addItem.php"); // Redirect back to the add item page to display the message
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
    $checkStmt->close();
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            font-family: 'Montserrat', sans-serif;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            padding: 0.5rem;
            font-size: 2rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group textarea {
            height: 120px;
            resize: vertical;
        }

        .submit-btn {
            padding: 1rem;
            background: black;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background 0.3s ease;
            font-family: 'Montserrat', sans-serif;
        }

        .submit-btn:hover {
            background: gray;
        }

        .back-btn {
            padding: 1rem;
            background: black;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            text-align: center; 
            width: fit-content; 
        }

        .back-btn:hover {
            background: gray;
        }
        
    </style>
</head>
<body>

<header>

</header>

<main>
    <div class="form-container">
        <h1>Add New Item</h1>
        <form method="POST" action="addItem.php" enctype="multipart/form-data" class="item-form">
            <div class="form-group">
                <label for="name">Item Name:</label>
                <input type="text" id="name" name="name" required maxlength="45">
            </div>

            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" required maxlength="45">
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" required maxlength="45">
            </div>

            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" required maxlength="45">
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <input type="text" id="gender" name="gender" required maxlength="45">
            </div>

            <div class="form-group">
                <label for="size">Size:</label>
                <input type="text" id="size" name="size" required maxlength="3">
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required step="0.01" min="0">
            </div>

            <div class="form-group">
                <label for="quantity">Available Quantity:</label>
                <input type="number" id="quantity" name="quantity" required min="0">
            </div>

            <div class="file-upload">
    <input type="file" id="imageFile" name="imageFile" accept="image/*" required>
    <p>Drag and drop an image or click to upload</p>
    <img id="previewImage" src="#" alt="Preview" style="display: none;">
    <button type="button" id="cancelImage" style="display: none;">Cancel</button> <!-- The cancel button -->
</div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
            <input type="submit" value="Add Item" class="submit-btn">
        </div>

            <div class="form-group">
                <a href="sellermode.php" class="back-btn">Back to Dashboard</a>
            </div>
        </form>
    </div>
</main>


<footer>
   
</footer>
<script>
   // Get the file input, preview image, and cancel button elements
const fileInput = document.getElementById('imageFile');
const previewImage = document.getElementById('previewImage');
const image_urlInput = document.getElementById('image_url');
const cancelImageButton = document.getElementById('cancelImage');

// Add event listener to the file input
fileInput.addEventListener('change', handleFileSelect);

// Function to handle file select
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        const image_url = URL.createObjectURL(file);
        previewImage.src = image_url;
        previewImage.style.display = 'block';
        cancelImageButton.style.display = 'inline'; // Show the cancel button
        image_urlInput.value = image_url;
    }
}

// Add event listeners to the file upload area for drag and drop
const fileUpload = document.querySelector('.file-upload');
fileUpload.addEventListener('dragover', function(event) {
    event.preventDefault();
    fileUpload.classList.add('dragover');
});
fileUpload.addEventListener('dragleave', function(event) {
    event.preventDefault();
    fileUpload.classList.remove('dragover');
});
fileUpload.addEventListener('drop', function(event) {
    event.preventDefault();
    fileUpload.classList.remove('dragover');
    // Use the handleFileSelect function here as well
    fileInput.files = event.dataTransfer.files;
    handleFileSelect({ target: fileInput });
});

// Add event listener to the cancel button
cancelImageButton.addEventListener('click', function() {
    // Clear the file input value
    fileInput.value = '';
    // Hide the preview image and cancel button
    previewImage.style.display = 'none';
    cancelImageButton.style.display = 'none';
    // Clear the image_url input value
    image_urlInput.value = '';
});
</script>

<?php if (isset($_SESSION['item_exists'])): ?>
    <?php unset($_SESSION['item_exists']); ?>
    <script>
    Swal.fire({
        title: "Duplicate Item!",
        text: "An item with these details already exists.",
        icon: "error",
        confirmButtonText: "Ok"
    });
    </script>
<?php elseif (isset($_SESSION['item_added'])): ?>
    <?php unset($_SESSION['item_added']); ?>
    <script>
    Swal.fire({
        title: "Success!",
        text: "Item added successfully",
        icon: "success",
        confirmButtonText: "Ok"
    });
    </script>
<?php endif; ?>
</body>
</html>