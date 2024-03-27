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
        $post_id = $row['post_id'];
        echo '<div class="post">';
        echo '<h2><strong>@' . $row['username'] . '</strong></h2>'; // Display the username
        echo '<p>' . $row['caption'] . '</p>'; // Display the caption
        echo '<img src="' . $row['image_URL'] . '" alt="Post Image">'; // Display the image

        // Display the like button
        if (isset($_SESSION['username'])) {
            echo '<form method="post" action="like.php">';
            echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
            echo '<button type="submit">Like</button>';
            echo '</form>';
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
?>