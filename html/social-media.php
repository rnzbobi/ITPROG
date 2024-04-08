<?php
session_start();
include 'database.php';

// Function to create the uploads directory if it doesn't exist
function createUploadsDirectory($dir) {
    if (!file_exists($dir)) {
        if (!mkdir($dir, 0777, true)) {
            die('Failed to create uploads directory.');
        }
    }
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    $loggedIn = false;
} else {
    $loggedIn = true;
    // Retrieve the user_id corresponding to the username
    $username = $_SESSION['username'];
    $sql = "SELECT userid FROM user_id WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['userid'];
    } else {
        // Handle the case where the user doesn't exist
        // For example, redirect the user to a login page
        exit("User not found");
    }
    $stmt->close();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = $_POST['caption'];
    $imageUrl = $_POST['imageUrl'];

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
            $imageUrl = $fileDestination;
        } else {
            $imageUrl = '';
        }
    }

    // Prepare and execute the SQL statement to insert the post into the database
    $sql = "INSERT INTO posts (user_id, caption, image_URL) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iss", $user_id, $caption, $imageUrl);
        if ($stmt->execute()) {
            echo "";
        } else {
            echo "Error executing statement: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index.html</title>
    <link rel="stylesheet" href="css/social_style.css" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
    <aside class="sidebar">
        <div class="logo">
            <a href="index.php">
                <img src="images/EGGHEAD(dark).png" alt="Logo">
            </a>
        </div>
        <div class="nav-links">
            <a href="index.php"><img src="images/shop.png" alt="Index"></a>
            <a href="view_cart.php"><img src="images/shopping-cart.png" alt="Cart"></a>
            <a href="user.php"><img src="images/user.png" alt="User"></a>
        </div>
        <div class="word-links">
            <?php
            if($loggedIn){
                echo '<button id="post-button"><span id="post-id">Post</span></button><br><br><br><br><br>';
                echo '<a href="user.php"><span id="user-id">Profile</span></a><br><br><br><br>';
                echo '<a href="logout.php"><span id="user-id">Logout</span></a><br><br><br>';
            } else {
                echo '<a href="login.php"><span id="user-id">Login/Signup</span></a><br><br><br>';
            }
            ?>
        </div>
    </aside>
    <main>
        <?php include 'display_posts.php'; ?>
    </main>
    <div id="postModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
                <label for="caption">Caption:</label><br>
                <input type="text" id="caption" name="caption" required><br>
                <div class="file-upload">
                    <input type="file" id="imageFile" name="imageFile" accept="image/*" required>
                    <p>Drag and drop an image or click to upload</p>
                    <img id="previewImage" src="#" alt="Preview" style="display: none;">
                </div>
                <input type="hidden" id="imageUrl" name="imageUrl">
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
    <script>
        // Get the modal
        const modal = document.getElementById('postModal');

        // Get the button that opens the modal
        const postButton = document.getElementById('post-button');

        // Get the <span> element that closes the modal
        const closeBtn = document.getElementsByClassName('close')[0];

        // When the user clicks the button, open the modal 
        postButton.onclick = function() {
            modal.style.display = 'block';
        }

        // When the user clicks on <span> (x), close the modal
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Get the file input and preview image elements
        const fileInput = document.getElementById('imageFile');
        const previewImage = document.getElementById('previewImage');
        const imageUrlInput = document.getElementById('imageUrl');

        // Add event listener to the file input
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            const imageUrl = URL.createObjectURL(file);
            previewImage.src = imageUrl;
            previewImage.style.display = 'block';
            imageUrlInput.value = imageUrl;
        });

        // Add event listener to the file upload area for drag and drop
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
            const file = event.dataTransfer.files[0];
            const imageUrl = URL.createObjectURL(file);
            previewImage.src = imageUrl;
            previewImage.style.display = 'block';
            imageUrlInput.value = imageUrl;
            fileInput.files = event.dataTransfer.files;
        });
    </script>
</body>
</html>