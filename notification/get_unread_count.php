<?php
include './includes/connection.php';

$user_id = intval($_GET['user_id']);
$query = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = $user_id AND is_read = 0";
$result = mysqli_query($con, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['unread_count' => $row['unread_count']]);
} else {
    echo "Error fetching unread count: " . mysqli_error($con);
}
?>
