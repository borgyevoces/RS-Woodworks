<?php
session_start();

include __DIR__ . '/includes/connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];
    $userId = $_SESSION['user_id'];  // Assuming you store user_id in session

    // SQL query to delete the item from cart_details table
    $query = "DELETE FROM cart_details WHERE product_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $productId, $userId);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Item removed from cart']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error removing item from cart']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database query error']);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($con);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No product ID provided']);
}
?>
