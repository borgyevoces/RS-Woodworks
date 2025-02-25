<?php
require_once 'admin_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_POST['adminId'];
    $username = $_POST['username'];
    $fullName = $_POST['fullName'];
    $adminEmail = $_POST['adminEmail'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $adminContact = $_POST['admin_contact'];

    $updateSuccess = updateAdmin($adminId, $username, $fullName, $adminEmail, $password, $role, $adminContact);

    if ($updateSuccess) {
        header('Location: adminpanel.php?update=success');
    } else {
        echo 'Error updating admin';
    }
}
?>
