<?php
include('../includes/connection.php'); 
// Check if the AJAX request is sent with the correct method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON data sent from the client
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract the data from the decoded JSON
    $orderId = mysqli_real_escape_string($con, $data['orderId']); // Prevent SQL injection
    $deliveryDate = mysqli_real_escape_string($con, $data['deliveryDate']); // Prevent SQL injection

    // Retrieve the order details including product details from the orders table
    $query = "SELECT o.*, u.full_name, u.user_email, u.user_address, p.product_name, p.product_price, p.product_image1
              FROM orders o 
              JOIN user_table u ON o.user_id = u.user_id 
              LEFT JOIN products p ON o.product_id = p.product_id 
              WHERE o.order_id = '$orderId'";

    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Extract order details
        $userId = $row['user_id'];
        $fullName = $row['full_name'];
        $userEmail = $row['user_email'];
        $productName = $row['product_name'];
        $productPrice = $row['product_price'];
        $quantity = $row['quantity']; // Assuming 'quantity' column exists in the 'orders' table
        $paymentStatus = $row['payment_status']; // Assuming 'payment_status' column exists in the 'orders' table
        $userAddress = $row['user_address'];
        $productId = $row['product_id']; // Fetching product_id
        $productImage = $row['product_image1']; // Fetching product_image
        $paymentMethod = $row['payment_method']; // Fetching payment_method

        // Check if any product detail is missing
        if ($productName !== null && $productPrice !== null && $productImage !== null) {
            // Insert the order details into the shipped_orders table
            $insertQuery = "INSERT INTO shipped_orders (user_id, full_name, user_email, product_name, product_price, quantity, payment_status, user_address, delivery_date, product_id, product_image, payment_method) 
                            VALUES ('$userId', '$fullName', '$userEmail', '$productName', '$productPrice', '$quantity', '$paymentStatus', '$userAddress', '$deliveryDate', '$productId', '$productImage', '$paymentMethod')";
            $insertResult = mysqli_query($con, $insertQuery);
            if ($insertResult) {
                // Order saved successfully, now delete from orders table
                $deleteQuery = "DELETE FROM orders WHERE order_id = '$orderId'";
                $deleteResult = mysqli_query($con, $deleteQuery);

                if ($deleteResult) {
                    // Order deleted successfully from orders table
                    echo json_encode(array('success' => true));
                } else {
                    // Error deleting order from orders table
                    echo json_encode(array('success' => false, 'message' => 'Error deleting order from orders table: ' . mysqli_error($con)));
                }
            } else {
                // Error saving order
                echo json_encode(array('success' => false, 'message' => 'Error saving order: ' . mysqli_error($con)));
            }
        } else {
            // One or more product details are missing
            echo json_encode(array('success' => false, 'message' => 'One or more product details are missing.'));
        }
    } else {
        // Order not found
        echo json_encode(array('success' => false, 'message' => 'Order not found.'));
    }
} else {
    // Method not allowed
    echo json_encode(array('success' => false, 'message' => 'Method not allowed.'));
}

?>
