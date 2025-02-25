<?php
include './includes/connection.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $query = "UPDATE notifications SET is_read = 1 WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "Notification marked as read!";
    } else {
        echo "Error marking notification as read: " . mysqli_error($con);
    }
} else {
    echo "No notification ID provided.";
}
?>
