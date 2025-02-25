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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if required parameters are set
    if (isset($_POST['message']) && isset($_POST['receiver_id'])) {
        $message = $_POST['message'];
        $receiver_id = $_POST['receiver_id']; // This should be the user ID
        $admin_username = $_SESSION['admin_username'];

        // Get admin ID based on username
        $query = "SELECT admin_id FROM admin_table WHERE username = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $admin_username);
        $stmt->execute();
        $stmt->bind_result($admin_id);
        $stmt->fetch();
        $stmt->close();

        // Check if admin_id was fetched successfully
        if ($admin_id !== null) {
            // Prepare the SQL statement to insert the message with sender_type set to 'admin'
            $insertQuery = "INSERT INTO messages (sender_id, receiver_id, message, sender_type, receiver_type) 
                            VALUES (?, ?, ?, 'admin', 'user')";
            $insertStmt = $con->prepare($insertQuery);
            $insertStmt->bind_param("iis", $admin_id, $receiver_id, $message); // sender_id, receiver_id, message

            // Execute the statement and check for errors
            if ($insertStmt->execute()) {
                echo "Message sent successfully!";
            } else {
                echo "Error: " . $con->error; // Check this error message
            }

            $insertStmt->close();
        } else {
            echo "Error: Admin ID not found.";
        }
    } else {
        echo "Error: Message or receiver ID not set.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$con->close();
?>
