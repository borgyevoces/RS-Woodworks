<?php
session_start();

// Connect to the database
$con=mysqli_connect('localhost', 'root', '','rswoodworks');

// Check connection
if (!$con) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Retrieve current user ID from session or other means
    $userId = $_SESSION['user_id'];

    // Retrieve current hashed password from the database
    $stmt = $con->prepare("SELECT password FROM user_table WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];

    // Verify the current password
    if (password_verify($currentPassword, $hashedPassword)) {
        // Check if the new password matches the confirmation
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateStmt = $con->prepare("UPDATE user_table SET password = ? WHERE user_id = ?");
            $updateStmt->bind_param("si", $hashedNewPassword, $userId);
            $updateStmt->execute();

            // Redirect to profile.php
            header("Location: ./profile.php?password_changed=1");
            exit();
        } else {
            $error = "New password and confirm password do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}
?>