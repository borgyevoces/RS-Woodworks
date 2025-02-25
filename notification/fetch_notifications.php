<?php
include './includes/connection.php';

$user_id = intval($_GET['user_id']);
$query = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($con, $query);

$notifications = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
    echo json_encode($notifications);
} else {
    echo "Error fetching notifications: " . mysqli_error($con);
}
?>
