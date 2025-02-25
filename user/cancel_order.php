<?php
$con=mysqli_connect('localhost', 'root', '','rswoodworks');

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
} else {
    
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['order_id'])) {
    $order_id = $data['order_id'];

    $query = "DELETE FROM orders WHERE order_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $order_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false]);
}
?>
