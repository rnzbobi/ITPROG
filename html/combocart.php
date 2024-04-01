<?php
session_start();
include 'database.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Function to find item_id by name and size
function findItemIdByNameAndSize($conn, $name, $size) {
    $query = "SELECT id FROM individual_clothes WHERE name = ? AND size = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $name, $size);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['id'];
    }
    return null;
}

// Handle the "Add to Cart" POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['combo_id'])) {
    $combo_id = $_POST['combo_id'];
    $updated_item_ids = [];

    // Fetch the current combo details
    $combo_query = "SELECT * FROM combo_clothes WHERE combo_id = ?";
    $combo_stmt = $conn->prepare($combo_query);
    $combo_stmt->bind_param("i", $combo_id);
    $combo_stmt->execute();
    $combo_result = $combo_stmt->get_result();
    if ($combo_result->num_rows > 0) {
        $combo_details = $combo_result->fetch_assoc();

        // Loop through each size selection and update the combo details
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_POST["size_$i"]) && !empty($combo_details["item_id$i"])) {
                $item_size = $_POST["size_$i"];
                $item_id = $combo_details["item_id$i"];

                // Get the name of the item
                $item_query = "SELECT name FROM individual_clothes WHERE id = ?";
                $item_stmt = $conn->prepare($item_query);
                $item_stmt->bind_param("i", $item_id);
                $item_stmt->execute();
                $item_result = $item_stmt->get_result();
                if ($item_result->num_rows > 0) {
                    $item_name = $item_result->fetch_assoc()['name'];
                    // Find the item ID with the selected size
                    $new_item_id = findItemIdByNameAndSize($conn, $item_name, $item_size);
                    if ($new_item_id) {
                        $updated_item_ids["item_id$i"] = $new_item_id;
                    }
                }
            }
        }
        // Now update the combo_clothes entry
        $update_query = "UPDATE combo_clothes SET item_id1 = ?, item_id2 = ?, item_id3 = ?, item_id4 = ? WHERE combo_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param(
            "iiiii",
            $updated_item_ids['item_id1'],
            $updated_item_ids['item_id2'],
            $updated_item_ids['item_id3'],
            $updated_item_ids['item_id4'],
            $combo_id
        );
        $update_stmt->execute();

        // Get the user_id for the current session's username
        $user_query = "SELECT userid FROM user_id WHERE username = ?";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bind_param("s", $_SESSION['username']);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        if ($user_result->num_rows > 0) {
            $user_id = $user_result->fetch_assoc()['userid'];

    // Prepare the cart insert statement
    $cart_query = "INSERT INTO carts (user_id, item_id, quantity, created_at) VALUES (?, ?, 1, NOW())";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("ii", $user_id, $combo_id);
    
    // Execute the cart insert statement
    $cart_stmt->execute();
    
    $_SESSION['item_added'] = true;
    
    // After adding to the cart, redirect to the cart view or give a success message.
    header("Location: index.php?item_id=".$combo_id); // Redirect to the view_combo.php with the item id
    exit();
} else {
    echo "User not found.";
}
        header("Location: index.php");
        exit();
    } else {
        echo "Combo not found.";
    }
} else {
    echo "Invalid request.";
}
?>