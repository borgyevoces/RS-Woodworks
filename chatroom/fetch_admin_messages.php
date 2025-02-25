<?php
session_start(); // Start the session at the top

// Check if the admin is logged in
if (!isset($_SESSION['admin_username']) || empty($_SESSION['admin_username'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

// Database connection
$con = new mysqli('localhost', 'root', '', 'rswoodworks');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Get user_id from the request
$user_id = $_GET['user_id'];

// Retrieve the admin's ID based on the session username
$adminQuery = "SELECT admin_id FROM admin_table WHERE username = ?";
$adminStmt = $con->prepare($adminQuery);
$adminStmt->bind_param("s", $_SESSION['admin_username']);
$adminStmt->execute();
$adminStmt->bind_result($admin_id);
$adminStmt->fetch();
$adminStmt->close();

// Prepare and execute the SQL query to retrieve both user and admin messages
$query = "SELECT m.message, m.timestamp, 
                 CASE WHEN m.sender_type = 'user' THEN u.username 
                      WHEN m.sender_type = 'admin' THEN a.username 
                 END AS sender_name,
                 m.sender_type
          FROM messages m
          LEFT JOIN user_table u ON m.sender_id = u.user_id AND m.sender_type = 'user'
          LEFT JOIN admin_table a ON m.sender_id = a.admin_id AND m.sender_type = 'admin'
          WHERE (m.sender_id = ? AND m.receiver_type = 'admin')
             OR (m.sender_id = ? AND m.receiver_type = 'user')
          ORDER BY m.timestamp ASC";
$stmt = $con->prepare($query);
$stmt->bind_param("ii", $user_id, $admin_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch and display messages
while ($message = $result->fetch_assoc()) {
    $text = htmlspecialchars($message['message']);
    $timestamp = date('d M h:i A', strtotime($message['timestamp'])); // Fixed from $row to $message
    $senderTypeClass = $message['sender_type'] === 'admin' ? 'admin' : 'user';

    // Set sender name to "You" if the admin is the sender
    $senderName = $message['sender_type'] === 'admin' ? 'You' : htmlspecialchars($message['sender_name']);

    // Set timestamp alignment based on sender type
    $timestampAlignment = $message['sender_type'] === 'admin' ? 'right' : 'left';

    echo "<div class='message $senderTypeClass'>
             $text
          </div>
          <div class='timestamp' style='text-align: $timestampAlignment;'>$timestamp</div>";
}

// Close the database connection
$stmt->close();
$con->close();
?>
