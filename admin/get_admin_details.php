<?php
require_once 'admin_functions.php';

if (isset($_GET['id'])) {
    $adminId = $_GET['id'];
    $adminDetails = fetchAdminById($adminId);

    if ($adminDetails) {
        echo json_encode($adminDetails);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Admin not found']);
    }
}
?>
