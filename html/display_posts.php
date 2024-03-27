<?php
// display_posts.php

include 'database.php';

// Fetch posts from the database
$sql = "SELECT p.post_id, p.user_id, p.caption, p.image_URL, u.username FROM posts p
        INNER JOIN user_id u ON p.user_id = u.userid
        ORDER BY p.post_id DESC"; // Order by most recent posts first
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Loop through each post and display it
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="post">';
        echo '<h2><strong>@' . $row['username'] . '</strong></h2>'; // Display the username
        echo '<p>' . $row['caption'] . '</p>'; // Display the caption
        echo '<img src="' . $row['image_URL'] . '" alt="Post Image">'; // Display the image
        // You can add more elements like like button, comment button, etc.
        echo '</div>';
    }
} else {
    echo 'No posts found.';
}

mysqli_close($conn);
?>
