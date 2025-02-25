<?php
session_start();
include __DIR__ . '/connection.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Update all unread messages (is_read = 0) for the user
    $stmt = $con->prepare("UPDATE messages SET is_read = 1 WHERE receiver_id = ? AND receiver_type = 'user' AND is_read = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $affected_rows = $stmt->affected_rows;
    $stmt->close();
    echo "success: " . $affected_rows;
} else {
    // If no user session, do nothing.
    echo "error: not a user session";
}
?>
