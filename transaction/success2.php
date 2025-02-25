<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['payment_success']) && $_GET['payment_success'] == 1) {
    if (!isset($_SESSION['user_id'])) {
        echo "Unauthorized access.";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $payment_method = $_SESSION['payment_method'] ?? 'PayMongo';
    $payment_status = 'Completed';
    $delivery_option = $_SESSION['delivery_option'] ?? 'Standard/120';
    $address = $_SESSION['user_address'];
    $transaction_id = bin2hex(random_bytes(10));

    $user_email = getUserEmail($con, $user_id);
    $user_contact = getUserContact($con, $user_id);
    if (!$user_email || !$user_contact) {
        echo "Error retrieving user information.";
        exit;
    }

    if (!isset($_SESSION['checkout_items']) || empty($_SESSION['checkout_items'])) {
        echo "Error: No checkout items found in session.";
        exit;
    }

    saveOrder($con, $user_id, $user_email, $user_contact, $payment_method, $payment_status, $delivery_option, $transaction_id, $address);

    echo "<script>alert('Transaction completed successfully!');</script>";
    $_SESSION['success_message'] = "Your payment was successful!";
    $product_id = $_SESSION['checkout_items'][0]['product_id'];
    echo "<script>setTimeout(function() { window.location.href = '../product_details.php?product_id=$product_id&payment_success=1'; }, 2000);</script>";
    exit;
} else {
    echo "Unauthorized access or payment not successful.";
    exit;
}

function getUserEmail($con, $user_id) {
    $query = "SELECT user_email FROM user_table WHERE user_id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_email);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $user_email;
}

function getUserContact($con, $user_id) {
    $query = "SELECT user_contact FROM user_table WHERE user_id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_contact);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $user_contact;
}

function saveOrder($con, $user_id, $user_email, $user_contact, $payment_method, $payment_status, $delivery_option, $transaction_id, $address) {
    $cart_items = $_SESSION['checkout_items'];
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'] ?? null;
        $product_name = $item['name'] ?? null;
        $product_price = $item['product_price'] ?? null;
        $product_image1 = $item['product_image1'] ?? null;
        $quantity = $item['quantity'] ?? null;

        if ($product_id === null || $product_name === null || $product_price === null || $product_image1 === null || $quantity === null) {
            echo "Error: Missing product information.";
            continue;
        }

        $insert_order_query = "INSERT INTO orders 
        (transaction_id, user_id, user_email, user_contact, product_id, product_name, product_price, product_image, quantity, payment_method, address, payment_status, delivery_option) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $insert_stmt = mysqli_prepare($con, $insert_order_query);
        mysqli_stmt_bind_param($insert_stmt, 'sississsissss', 
            $transaction_id, $user_id, $user_email, $user_contact, $product_id, $product_name, $product_price, $product_image1, $quantity, $payment_method, $address, $payment_status, $delivery_option);

        if (!mysqli_stmt_execute($insert_stmt)) {
            echo "Error saving order: " . mysqli_error($con);
        }
    }
}
?>
