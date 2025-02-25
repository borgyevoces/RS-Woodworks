
<?php
include('../includes/connection.php'); 

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate inputs
if (!isset($data['product_id'], $data['stock'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit();
}

$product_id = intval($data['product_id']);
$stock = intval($data['stock']);

// Update stock in the database
$query = "UPDATE products SET stock = $stock WHERE product_id = $product_id";
$result = mysqli_query($con, $query);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($con)]);
}
?>
