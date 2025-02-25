<?php
// Start the session
session_start();

include('../includes/connection.php'); 

// Check if the user ID is set in the session
if (!isset($_SESSION['user_id'])) {
    echo "User ID not set in session.";
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize inputs
    $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    
    // Get custom address fields
    $region = mysqli_real_escape_string($con, $_POST['region']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $street_building = mysqli_real_escape_string($con, $_POST['street_building']);
    $unit_floor = mysqli_real_escape_string($con, $_POST['unit_floor']);
    
    // Concatenate address components into a single address string
    $address = $region . ", " . $city . ", " . $street_building . ", " . $unit_floor;

    // Update user information in the database
    $updateQuery = "UPDATE user_table SET 
                        full_name = '$full_name', 
                        user_email = '$email', 
                        user_contact = '$contact', 
                        user_address = '$address' 
                    WHERE user_id = $user_id";

    // Execute update query
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Set session variable to indicate success
        $_SESSION['update_success'] = true;
        // Redirect back to profile.php
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating user information: " . mysqli_error($con);
    }
}
?>
