<?php 
session_start();
$con = new mysqli('localhost', 'root', '', 'rswoodworks');
// fetch_new_message_count.php
// Assuming you have user_id stored in the session
$user_id = $_SESSION['user_id'];  // Replace with actual session data
$query = "SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND status = 'unread'";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($newMessageCount);
$stmt->fetch();
$stmt->close();

echo $newMessageCount;  // Return the count as a response
?>


?>