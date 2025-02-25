<?php
include './includes/connection.php';

if (isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $query = "UPDATE notifications SET is_read = 1 WHERE user_id = $user_id";
    if (mysqli_query($con, $query)) {
        echo "All notifications marked as read!";
    } else {
        echo "Error marking notifications as read: " . mysqli_error($con);
    }
} else {
    echo "No user ID provided.";
}
?>
