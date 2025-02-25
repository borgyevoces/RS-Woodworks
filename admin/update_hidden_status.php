<?php
include('../includes/connection.php'); 

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['product_id']) && isset($data['hidden'])) {
    $product_id = $data['product_id'];
    $hidden = $data['hidden'];

    $query = "UPDATE products SET hidden = '$hidden' WHERE product_id = $product_id";
    if (mysqli_query($con, $query)) {
        echo "Hidden status updated successfully.";
    } else {
        echo "Error updating hidden status: " . mysqli_error($con);
    }
}
?>
