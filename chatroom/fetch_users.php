<?php
session_start();
$con = new mysqli('localhost', 'root', '', 'rswoodworks');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$query = "SELECT DISTINCT u.user_id, u.username, u.user_image
          FROM messages AS m 
          JOIN user_table AS u ON m.sender_id = u.user_id 
          WHERE m.receiver_type = 'admin'";

$result = $con->query($query);

while ($row = $result->fetch_assoc()) {
    $userId = $row['user_id'];
    $full_name= htmlspecialchars($row['full_name']);
    $userImage = htmlspecialchars($row['user_image']);

    echo "<div class='user-item' data-user-id='$userId' data-username='$username' onclick='selectUser(this)'>
            <img src=''./user/user_images/$userImage' alt='$username'>
            <span>$full_name</span>
          </div>";
}

$con->close();
?>
