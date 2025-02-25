<?php
include 'includes/connection.php'; // Include your database connection file

if (isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Update quantity in the database
    $update_query = "UPDATE cart_details SET quantity = $quantity WHERE product_id = $product_id";
    $update_result = mysqli_query($con, $update_query);
    if ($update_result) {
        echo "Quantity updated successfully";
    } else {
        echo "Error updating quantity: " . mysqli_error($con);
    }
} else {
    echo "Invalid request";
}
?>