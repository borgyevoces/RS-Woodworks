<?php
session_start(); // Start the session

// Capture the current page URL
$current_page = $_SERVER['REQUEST_URI'];

// Check if the user is logged in
if(isset($_SESSION['username'])) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the current page
    header("Location: ../home.php");
    exit;
} elseif(isset($_SESSION['admin_username'])) {
    // Unset all of the admin session variables
    $_SESSION = array();

    // Destroy the admin session
    session_destroy();

    // Redirect to the current page
    header("Location: ../user/login.php");
    exit;
}

// If the user is not logged in, they shouldn't reach this point.
// If they somehow do, you can redirect them to the homepage or any other desired page.
header("Location: ../home.php");
exit;
?>