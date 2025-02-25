<?php
include './includes/connection.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $query = "DELETE FROM notifications WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "Notification deleted successfully!";
    } else {
        echo "Error deleting notification: " . mysqli_error($con);
    }
} else {
    echo "No notification ID provided.";
}
?>
