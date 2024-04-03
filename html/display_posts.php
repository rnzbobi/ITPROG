<?php
// display_posts.php
include 'database.php';

// Function to get the item_id from the image_URL
function getItemIdFromImageUrl($imageUrl, $conn) {
    $sql = "SELECT id FROM individual_clothes WHERE image_URL = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $imageUrl);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $itemId = $row['id'];
        $stmt->close();
        return $itemId;
    } else {
        $stmt->close();
        return null;
    }
}

// Fetch posts from the database
$sql = "SELECT p.post_id, p.user_id, p.caption, p.image_URL, u.username FROM posts p INNER JOIN user_id u ON p.user_id = u.userid ORDER BY p.post_id DESC";
// Order by most recent posts first
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Loop through each post and display it
    while ($row = mysqli_fetch_assoc($result)) {
        $post_id = $row['post_id'];
        $post_user_id = $row['user_id'];
        echo '<div class="post">';
        echo '<h2><strong>@' . $row['username'] . '</strong></h2>'; // Display the username
        echo '<p>' . $row['caption'] . '</p>'; // Display the caption
        
        // Get the item_id from the image_URL
        $itemId = getItemIdFromImageUrl($row['image_URL'], $conn);
        
        // If the item_id is found, make the image clickable
        if ($itemId !== null) {
            echo '<a href="view.php?item_id=' . $itemId . '"><img src="' . $row['image_URL'] . '" alt="Post Image"></a>';
        } else {
            echo '<img src="' . $row['image_URL'] . '" alt="Post Image">'; // Display the image without a link
        }

        // Check if the user is logged in and has already liked the post
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            $sql_check_like = "SELECT * FROM likes WHERE post_id = $post_id AND user_id = (SELECT userid FROM user_id WHERE username = '$username')";
            $result_check_like = mysqli_query($conn, $sql_check_like);
            if (mysqli_num_rows($result_check_like) > 0) {
                // User has already liked the post
                echo '<div class="button-container">'; // Add a container for the buttons
                echo '<form method="post" action="like.php">';
                echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                echo '<button type="submit" class="liked">Unlike</button>';
                echo '</form>';
            } else {
                // User hasn't liked the post yet
                echo '<div class="button-container">'; // Add a container for the buttons
                echo '<form method="post" action="like.php">';
                echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                echo '<button type="submit">Like</button>';
                echo '</form>';
            }

            // Check if the logged-in user is the owner of the post
            $logged_in_user_id = getUserIdFromUsername($_SESSION['username'], $conn);
            if ($logged_in_user_id == $post_user_id) {
                echo '<form method="get" action="edit_post.php">';
                echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                echo '<button type="submit">Edit</button>';
                echo '</form>';
                echo '<form method="post" action="delete_post.php">';
                echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                echo '<button type="submit">Delete</button>';
                echo '</form>';
            }
            echo '</div>'; // Close the button container
        }

        // Display the number of likes
        $sql_likes = "SELECT COUNT(*) AS num_likes FROM likes WHERE post_id = $post_id";
        $result_likes = mysqli_query($conn, $sql_likes);
        $row_likes = mysqli_fetch_assoc($result_likes);
        $num_likes = $row_likes['num_likes'];
        echo '<p>' . $num_likes . ' Likes</p>';
        echo '</div>';
    }
} else {
    echo 'No posts found.';
}

mysqli_close($conn);

function getUserIdFromUsername($username, $conn)
{
    $sql = "SELECT userid FROM user_id WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['userid'];
}
?>