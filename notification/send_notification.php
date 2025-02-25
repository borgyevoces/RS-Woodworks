<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    echo "Access denied. Please log in as an admin.";
    exit();
}

include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $message = $_POST['message'];
    $user_id = $_POST['user_id'];
    $recipient = $user_id == 'all' ? 'all' : $user_id;

    // Log the notification submission
    error_log("Notification submitted: Type - $type, Message - $message, User ID - $user_id");

    if ($user_id == 'all') {
        // Send notification to all users
        $sql = "INSERT INTO notifications (user_id, message, type, recipient) SELECT user_id, ?, ?, ? FROM user_table";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $message, $type, $recipient);
    } else {
        // Get the full name and username of the user
        $stmt = $con->prepare("SELECT full_name, username FROM user_table WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($full_name, $username);
        $stmt->fetch();
        $stmt->close();

        // Send notification to a specific user
        $recipient_info = "Full Name: $full_name, Username: $username, User ID: $user_id";
        $stmt = $con->prepare("INSERT INTO notifications (user_id, message, type, recipient) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $message, $type, $recipient_info);
    }

    if ($stmt->execute()) {
        echo "Notification sent!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
