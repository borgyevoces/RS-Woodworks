<?php
// Include connection file
include '../includes/connection.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit;
}

// Function to generate random transaction ID
function generateTransactionID() {
    return uniqid('transaction_');
}

// Function to save a specific product to the orders table
function saveProductToOrdersTable($con, $product_id, $quantity) {
    // Generate transaction ID
    $transactionID = generateTransactionID();

    // Get user details from session
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $user_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : null;
    $user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $delivery_option = $_SESSION['delivery_option'] ?? 'Standard/120';

    // Default payment method for all orders (Cash On Delivery)
    $payment_method = 'Cash On Delivery';

    // Fetch product details from the products table
    $product_query = "SELECT product_name, product_price, product_image1 FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($con, $product_query);
    mysqli_stmt_bind_param($stmt, 'i', $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$product) {
        echo "Product not found.";
        return false;
    }

    // Extract product details
    $product_name = $product['product_name'];
    $product_price = $product['product_price'];
    $product_image = $product['product_image1'];

    // Set additional order details
    $payment_status = 'Pending';
    $date = date('Y-m-d H:i:s');

    // Prepare the INSERT statement
    $insert_query = "INSERT INTO orders (user_id, transaction_id, product_id, product_name, product_price, product_image, payment_method, address, user_ip, user_email, payment_status, date, quantity, delivery_option) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $insert_query);
    if ($stmt) {
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmt, 'isdsssssssssis', $user_id, $transactionID, $product_id, $product_name, $product_price, $product_image, $payment_method, $user_address, $user_ip, $user_email, $payment_status, $date, $quantity, $delivery_option);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
        return false;
    }

    // All insertions successful, return true
    return true;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product ID and quantity from POST data
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Process the product (e.g., save to the database)
    if (saveProductToOrdersTable($con, $product_id, $quantity)) {
        // Set success message in session
        $_SESSION['success_message'] = 'Product ordered successfully.';
        // Redirect to product_details.php with success parameter
        header("Location: ../product_details.php?product_id=$product_id&success=true");
        exit;
    } else {
        // Error saving product
        echo json_encode(array('success' => false, 'message' => 'Error ordering product.'));
    }
}

// Redirect if the form was not submitted
// Only redirect to cart.php if it's not an AJAX request
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    $_SESSION['success_message'] = "Your transaction was successful!";
    header("Location: ../cart.php?success=true");
    exit;
}

// Close database connection
mysqli_close($con);
?>
