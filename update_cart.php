<?php
session_start();
include __DIR__ . '/includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in (optional, depends on your requirements)
    if (!isset($_SESSION['user_id'])) {
        echo "Unauthorized access.";
        exit();
    }

    // Validate and sanitize input
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = intval($_POST['product_id']); // Ensure it's an integer
        $quantity = max(1, intval($_POST['quantity'])); // Ensure quantity is at least 1

        // Log to file for debugging
        file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Received Product ID: $productId, Quantity: $quantity\n", FILE_APPEND);

        // Use prepared statement to prevent SQL injection
        $stmt = $con->prepare("UPDATE cart_details SET quantity = ? WHERE product_id = ?");
        $stmt->bind_param("ii", $quantity, $productId);

        if ($stmt->execute()) {
            echo "Quantity updated successfully.";
        } else {
            echo "Error updating quantity: " . $stmt->error;
        }

        $stmt->close(); // Close the prepared statement
    } else {
        echo "Invalid request: Product ID or quantity missing.";
    }
}

mysqli_close($con); // Close the database connection
?>
