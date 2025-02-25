<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

include '../includes/connection.php';
$user_id = $_SESSION['user_id'];

if (isset($_POST['indexes']) && is_array($_POST['indexes'])) {
    $indexes = $_POST['indexes'];
    $placeholders = implode(',', array_fill(0, count($indexes), '?'));
    $types = str_repeat('i', count($indexes));
    $stmt = $con->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND id IN ($placeholders)");
    $stmt->bind_param("i" . $types, $user_id, ...$indexes);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No notifications selected']);
}
?>
