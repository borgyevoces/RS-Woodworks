<?php
include('../includes/connection.php'); 

function checkRole($required_role) {

    // Check if the user is logged in (adjust according to your session variable names)
    if (!isset($_SESSION['admin_username']) && !isset($_SESSION['username'])) {
        echo "Access denied. Please log in first.";
        exit(); // Exit if the user is not logged in
    }

    // Determine the session variable holding the username and retrieve the user's role from the database
    $username = $_SESSION['admin_username'] ?? $_SESSION['username']; // Use admin_username or username as needed

    // Include your database connection code
    include '../includes/connection.php';

    // Prepare a query to get the user's role
    $query = "SELECT role FROM `admin_table` WHERE username = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_role = $row['role'];

        // Check if the user's role matches the required role
        if ($user_role !== $required_role) {
            echo "Access denied. You do not have permission to view this page.";
            exit(); // Exit if the roles do not match
        }
    } else {
        // Handle case where the username does not exist in the database
        echo "Access denied. User role could not be determined.";
        exit(); // Exit if the user role is not found
    }
}


// Check Admin Authentication
function checkAuth() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: adminpanel.php');
        exit();
    }
}
// Fetch All Admins
function fetchAdmins() {
    global $con;
    $query = "SELECT * FROM admin_table";
    return mysqli_query($con, $query);
}

function fetchAdminById($adminId) {
    global $con;
    $query = "SELECT * FROM admin_table WHERE admin_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function updateAdmin($adminId, $username, $fullName, $adminEmail, $password, $role) {
    global $con;
    $query = "UPDATE admin_table SET username = ?, full_name = ?, admin_email = ?, password = ?, role = ? WHERE admin_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssssi", $username, $fullName, $adminEmail, $password, $role, $adminId);
    return $stmt->execute();
}

?>
