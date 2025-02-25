<?php
session_start();

if (isset($_POST['userId']) && is_numeric($_POST['userId'])) {
    $userId = $_POST['userId'];
    $con = mysqli_connect('localhost', 'root', '', 'rswoodworks');

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }

    // Delete related messages first
    $deleteMessagesQuery = "DELETE FROM messages WHERE receiver_id = ?";
    if ($msgStmt = mysqli_prepare($con, $deleteMessagesQuery)) {
        mysqli_stmt_bind_param($msgStmt, 'i', $userId);
        mysqli_stmt_execute($msgStmt);
        mysqli_stmt_close($msgStmt);
    } else {
        echo "Error preparing messages delete statement: " . mysqli_error($con);
        mysqli_close($con);
        exit;
    }

    // Delete the user
    $query = "DELETE FROM user_table WHERE user_id = ?";
    if ($stmt = mysqli_prepare($con, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION['success_message'] = "User deleted successfully";
                header("Location: ../admin/adminpanel.php#user-table");
                exit;
            } else {
                echo "User not found or already deleted";
            }
        } else {
            echo "Error executing query: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }

    mysqli_close($con);
} else {
    echo "Invalid or missing user ID";
}
?>
