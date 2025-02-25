<?php
session_start();

require_once 'admin_functions.php';
checkRole('super_admin');

if (isset($_GET['id'])) {
    $adminId = intval($_GET['id']); // Sanitize input

    // Ensure the admin is not deleting themselves (if needed)
    if ($adminId !== $_SESSION['admin_id']) {
        $query = "DELETE FROM admin_table WHERE admin_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $adminId);

        if ($stmt->execute()) {
            // Redirect with a success message
            header("Location: adminpanel.php?message=Admin deleted successfully");
        } else {
            // Redirect with an error message
            header("Location: adminpanel.php?error=Failed to delete admin");
        }
        $stmt->close();
    } else {
        header("Location: adminpanel.php?error=Cannot delete yourself");
    }
} else {
    header("Location:adminpanel.php?error=Invalid admin ID");
}
?>
