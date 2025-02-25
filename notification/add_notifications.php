<?php
include './includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = mysqli_real_escape_string($con, $_POST['message']);
    $user_id = intval($_POST['user_id']);
    $query = "INSERT INTO notifications (message, user_id) VALUES ('$message', $user_id)";
    if (mysqli_query($con, $query)) {
        echo "Notification added successfully!";
    } else {
        echo "Error adding notification: " . mysqli_error($con);
    }
} else {
    echo "Invalid request method.";
}
?>
