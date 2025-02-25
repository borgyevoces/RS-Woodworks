<?php
require_once 'admin_functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
    $admin_email = mysqli_real_escape_string($con, $_POST['admin_email']);
    $role = $_POST['role'];

    $query = "INSERT INTO admin_table (username, password, full_name, admin_email, role) 
              VALUES ('$username', '$password', '$full_name', '$admin_email', '$role')";
    mysqli_query($con, $query);
    header('Location: admin_list.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
</head>
<body>
    <h1>Add Admin</h1>
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <label>Full Name:</label><br>
        <input type="text" name="full_name" required><br>
        <label>Email:</label><br>
        <input type="email" name="admin_email" required><br>
        <label>Role:</label><br>
        <select name="role">
            <option value="super_admin">Super Admin</option>
            <option value="manager">Manager</option>
            <option value="editor">Editor</option>
        </select><br>
        <button type="submit">Add Admin</button>
    </form>
</body>
</html>
