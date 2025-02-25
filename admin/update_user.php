<?php
// update_user.php

// Start session if needed (for flash messages, etc.)
session_start();

// Include your database connection file
include('../includes/connection.php'); 

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $user_id      = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $username     = trim($_POST['username']);
    $user_email   = trim($_POST['user_email']);
    $user_contact = trim($_POST['user_contact']);
    $user_address = trim($_POST['user_address']);

    // Validate required fields
    if (empty($username) || empty($user_email)) {
        // Redirect back with an error message if required fields are missing
        header("Location: ../admin/users.php?error=Please+fill+in+required+fields");
        exit;
    }

    // Prepare the SQL update statement
    $stmt = $con->prepare("UPDATE user_table SET username = ?, user_email = ?, user_contact = ?, user_address = ? WHERE user_id = ?");
    if (!$stmt) {
        header("Location: ../admin/users.php?error=Database+error:+Unable+to+prepare+statement");
        exit;
    }

    // Bind parameters: "ssssi" means string, string, string, string, integer
    $stmt->bind_param("ssssi", $username, $user_email, $user_contact, $user_address, $user_id);

    // Execute the query and check for success
    if ($stmt->execute()) {
        // Optionally, set a session variable for success messages
        $_SESSION['success'] = "User updated successfully.";
        header("Location: ../admin/users.php#user-table");
        exit;
    } else {
        header("Location: ../admin/users.php?error=Failed+to+update+user");
        exit;
    }

    // Close the statement
    $stmt->close();
}
?>
