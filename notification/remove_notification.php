<?php
session_start();
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    echo "Access denied. Please log in.";
    exit();
}

include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $indexes = $_POST['indexes'] ?? [];

    if (!empty($indexes)) {
        foreach ($indexes as $index) {
            // Check if the notification is sent to all users
            $stmt = $con->prepare("SELECT recipient FROM notifications WHERE id = ?");
            $stmt->bind_param("i", $index);
            $stmt->execute();
            $stmt->bind_result($recipient);
            $stmt->fetch();
            $stmt->close();

            if ($recipient == 'all') {
                // Delete all notifications with the same message
                $stmt = $con->prepare("DELETE FROM notifications WHERE message = (SELECT message FROM notifications WHERE id = ?)");
                $stmt->bind_param("i", $index);
            } else {
                // Delete the specific notification
                $stmt = $con->prepare("DELETE FROM notifications WHERE id = ?");
                $stmt->bind_param("i", $index);
            }
            $stmt->execute();
            $stmt->close();
        }
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    echo "Invalid request method.";
}
?>
