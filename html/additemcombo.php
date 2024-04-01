<?php
session_start();
include 'database.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$itemOptions = '';
$itemNames = array(); // Initialize an array to keep track of item names
$query = "SELECT id, name FROM individual_clothes";
if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
        // Check if the item's name is already in the array
        if (!in_array($row['name'], $itemNames)) {
            $itemOptions .= "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
            $itemNames[] = $row['name']; // Mark this name as included
        }
    }
    $result->close();
}

// Handle the POST request from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input from the form
    $combo_name = $_POST['combo_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $item_id1 = $_POST['item_id1'] !== '' ? $_POST['item_id1'] : null;
    $item_id2 = $_POST['item_id2'] !== '' ? $_POST['item_id2'] : null;
    $item_id3 = $_POST['item_id3'] !== '' ? $_POST['item_id3'] : null;
    $item_id4 = $_POST['item_id4'] !== '' ? $_POST['item_id4'] : null;
    $available_quantity = $_POST['available_quantity'];
    $image_URL = $_POST['image_URL'];
    $total_quantity = $available_quantity; // Set total_quantity to the same as available_quantity

    // Update the prepared statement to include new fields
    $stmt = $conn->prepare("INSERT INTO combo_clothes (item_id1, item_id2, item_id3, item_id4, combo_name, description, price, available_quantity, image_URL, total_quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind the new parameters as well
    $stmt->bind_param("iiissssisi", $item_id1, $item_id2, $item_id3, $item_id4, $combo_name, $description, $price, $available_quantity, $image_URL, $total_quantity);

    $noneSelected = array_filter([$item_id1, $item_id2, $item_id3, $item_id4], function($value) {
        return $value === null; // Or check for an empty string or whatever indicates 'None'
    });

    if (count($noneSelected) > 2) {
        $_SESSION['error_message'] = "You cannot leave more than two items as 'None'";
        header("Location: additemcombo.php");
        exit();
    }
    // Execute and check for success
    if ($stmt->execute()) {
        // If execute is successful
        $_SESSION['item_added'] = "Combo added successfully!";
        header("Location: additemcombo.php"); // Redirect to success page
        exit();
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;1,200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('.item-form');
    const selects = document.querySelectorAll('.item-select');

    form.addEventListener('submit', function(event) {
        let noneCount = 0;
        let selectedValues = new Set(); // Use a Set to track unique selections

        for (let select of selects) {
            if (select.value === '') {
                noneCount++;
            } else {
                if (selectedValues.has(select.value)) {
                    // If the value is already in the set, it's a duplicate
                    event.preventDefault(); // Stop the form from submitting
                    Swal.fire({
                        title: "Error!",
                        text: "Each item must be unique. Please do not select the same item more than once.",
                        icon: "error",
                        confirmButtonText: "Ok"
                    });
                    return; // Exit the function early
                } else {
                    selectedValues.add(select.value); // Add unique value to the set
                }
            }
        }

        if (noneCount > 2) {
            event.preventDefault(); // Stop the form from submitting
            Swal.fire({
                title: "Error!",
                text: "You cannot leave more than two items as 'None'",
                icon: "error",
                confirmButtonText: "Ok"
            });
        }
    });
});
</script>

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
    <h1>Add New Combo</h1>
    <form method="POST" class="item-form">
        <!-- Fields for the combo items' IDs -->
        <div class="form-group">
                <label for="item_id1">Item ID 1:</label>
                <select id="item_id1" name="item_id1" class="item-select">
                        <option value="">None</option>
                        <?php echo $itemOptions; ?>
                    </select>
            </div>

        <div class="form-group">
                <label for="item_id2">Item ID 2:</label>
                <select id="item_id2" name="item_id2" class="item-select">
                        <option value="">None</option>
                        <?php echo $itemOptions; ?>
                    </select>
            </div>

            <div class="form-group">
                <label for="item_id3">Item ID 3:</label>
                <select id="item_id3" name="item_id3" class="item-select">
                        <option value="">None</option>
                        <?php echo $itemOptions; ?>
                    </select>
            </div>

            <div class="form-group">
                <label for="item_id4">Item ID 4:</label>
                <select id="item_id4" name="item_id4" class="item-select">
                        <option value="">None</option>
                        <?php echo $itemOptions; ?>
                    </select>
            </div>

        <div class="form-group">
            <label for="combo_name">Combo Name:</label>
            <input type="text" id="combo_name" name="combo_name" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required step="0.01" min="0">
        </div>

        <div class="form-group">
            <label for="available_quantity">Available Quantity:</label>
            <input type="number" id="available_quantity" name="available_quantity" required min="0">
        </div>

        <div class="form-group">
            <label for="image_url">Image URL:</label>
            <input type="text" id="image_URL" name="image_URL" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <div class="form-group">
            <input type="submit" value="Add Combo" class="submit-btn">
        </div>

        <div class="form-group">
                <a href="sellermode.php" class="back-btn">Back to Dashboard</a>
            </div>
    </form>
</div>

</main>


<footer>
   
</footer>

<?php if (isset($_SESSION['item_added'])): ?>
    <?php unset($_SESSION['item_added']); ?>
    <script>
    console.log('Attempting to show SweetAlert2 notification.'); 
    Swal.fire({
        title: "Success!",
        text: "Item added",
        icon: "success",
        confirmButtonText: "Ok"
    }).then(function() {
        
    });
    </script>
<?php endif; ?>

</body>
</html>