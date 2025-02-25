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

// Function to save cart items to the orders table
function saveCartItemsToOrdersTable($con, $user_id, $cartItems) {
    // Generate transaction ID
    $transactionID = generateTransactionID();

    // Get user details from session
    $user_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : null;
    $user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $delivery_option = $_SESSION['delivery_option'] ?? 'Standard/120';

    // Default payment method for all orders (Cash On Delivery)
    $payment_method = 'Cash On Delivery';

    // Iterate through cart items
    foreach ($cartItems as $item) {
        // Extract item details
        $product_id = $item['product_id'];
        $product_price = $item['product_price'];
        $product_name = $item['product_name'];
        $quantity = $item['quantity'];
        $product_image = $item['product_image1'];

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
    }

    // Remove all cart items for the user
    $delete_query = "DELETE FROM cart_details WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // All insertions successful, return true
    return true;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve cart items for the logged-in user
    $user_id = $_SESSION['user_id'];
    $cart_query = "SELECT * FROM cart_details WHERE user_id = $user_id";
    $result = mysqli_query($con, $cart_query);

    // Initialize an array to store cart items
    $cartItems = [];

    // Loop through cart items and store them in the array
    while ($row = mysqli_fetch_assoc($result)) {
        // Fetch product details from the products table using the product ID
        $product_query = "SELECT product_name, product_price, product_image1 FROM products WHERE product_id = " . $row['product_id'];
        $product_result = mysqli_query($con, $product_query);
        $product_row = mysqli_fetch_assoc($product_result);

        // Merge product details with cart item
        $cartItem = array_merge($row, $product_row);

        // Add merged cart item to the array
        $cartItems[] = $cartItem;
    }

    // Process the cart items (e.g., save to the database)
    if (saveCartItemsToOrdersTable($con, $user_id, $cartItems)) {
        // Set success message in session
        $_SESSION['success_message'] = 'Cart items ordered successfully.';
        // Redirect to cart.php with success parameter
        header("Location: ../cart.php?success=true");
        exit;
    } else {
        // Error saving cart items
        echo json_encode(array('success' => false, 'message' => 'Error ordering cart items.'));
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
