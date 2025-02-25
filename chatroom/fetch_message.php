<?php
session_start();
$con = new mysqli('localhost', 'root', '', 'rswoodworks');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$user_id = $_SESSION['user_id'] ?? 0;
$is_admin = $_SESSION['is_admin'] ?? false;

// Prepare the SQL query for fetching messages
if ($is_admin) {
    // Admin can see all messages sent to them by users
    $query = "SELECT sender_id, message, timestamp 
              FROM messages 
              WHERE receiver_type = 'admin' 
              ORDER BY timestamp ASC";
    $result = $con->query($query); // Direct query for admin
} else {
    // User can see messages sent to them by admin and their own messages
    $query = "SELECT sender_id, message, timestamp 
              FROM messages 
              WHERE (receiver_id = ? AND receiver_type = 'user') 
              OR (sender_id = ? AND sender_type = 'user') 
              ORDER BY timestamp ASC";
    
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result(); // Get result from the prepared statement
}

// Display messages
while ($row = ($is_admin ? $result->fetch_assoc() : $result->fetch_assoc())) {
    $sender_id = $row['sender_id'];
    $message = htmlspecialchars($row['message']);
    // Format timestamp to display day, month, and time
    $timestamp = date('d M h:i A', strtotime($row['timestamp'])); // e.g., "28 Oct 03:45 PM"

    // Check who sent the message
    $is_sent_by_user = ($sender_id == $user_id);

    // Style messages differently for sender and receiver
    if ($is_sent_by_user) {
        echo "<div class='message user'>
                <span>$message</span>
                <span class='timestamp'>$timestamp</span>
              </div>";
    } else {
        echo "<div class='message admin'>
                <span>$message</span>
                <span class='timestamp'>$timestamp</span>
              </div>";
    }
}

// Close connections
if (!$is_admin) {
    $stmt->close(); // Close prepared statement if used
}
$con->close();
?>
