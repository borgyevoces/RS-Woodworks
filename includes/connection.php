<?php
// Establish connection to the database
$con = mysqli_connect('localhost', 'root', '', 'rswoodworks');

// Check connection
if (mysqli_connect_errno()) {
    trigger_error("Failed to connect to MySQL: " . mysqli_connect_error(), E_USER_ERROR);
    exit();
}
?>
