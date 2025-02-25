<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

include('../includes/connection.php'); 

// Fetch the user's address from the user_table based on user_id
$user_id = $_SESSION['user_id'];
$user_address_query = "SELECT user_address FROM user_table WHERE user_id = ?";
$user_address_stmt = $con->prepare($user_address_query);
$user_address_stmt->bind_param("i", $user_id);
$user_address_stmt->execute();
$user_address_stmt->bind_result($user_address);
$user_address_stmt->fetch();
$user_address_stmt->close();

// Fetch user contact and full name from the user_table based on user_id
$user_contact_query = "SELECT user_contact, full_name FROM user_table WHERE user_id = ?";
$user_contact_stmt = $con->prepare($user_contact_query);
$user_contact_stmt->bind_param("i", $user_id);
$user_contact_stmt->execute();
$user_contact_stmt->bind_result($user_contact, $full_name);
$user_contact_stmt->fetch();
$user_contact_stmt->close();

// If user address is not found in the database, send an error
if (empty($user_address)) {
    echo json_encode(['error' => 'User address is not found.']);
    exit();
}

// Ensure data is being received from the checkout modal
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']); // Ensure quantity is an integer
$delivery_fee = intval($_POST['delivery_fee']); // Ensure delivery_fee is an integer
$delivery_option = $_POST['delivery_option']; // Get the selected delivery option

// Fetch product details from the database
$product_query = "SELECT product_name, product_price, product_image1 FROM products WHERE product_id = ?";
$product_stmt = $con->prepare($product_query);
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product_stmt->bind_result($product_name, $product_price, $product_image);
$product_stmt->fetch();
$product_stmt->close();

// Calculate subtotal for the product
$subtotal = $product_price * $quantity;

// Prepare cart items for PayMongo (includes delivery fee)
$cart_items = [
    [
        'product_id' => $product_id,  // Add product_id
        'currency' => 'PHP',
        'amount' => (int)($product_price * 100), // Convert to cents
        'name' => $product_name,
        'quantity' => $quantity, // Ensure quantity is passed as integer
        'product_name' => $product_name, // Add product_name
        'product_price' => $product_price, // Add product_price
        'product_image1' => $product_image, // Add product_image
    ],
    [
        'currency' => 'PHP',
        'amount' => (int)($delivery_fee * 100), // Delivery fee in cents
        'name' => 'Delivery Fee',
        'quantity' => 1, // Always 1 for delivery fee
    ]
];

// Calculate total amount in cents
$total_amount_in_cents = ($subtotal + $delivery_fee) * 100;

// Prepare data for PayMongo checkout session
$body = [
    'data' => [
        'attributes' => [
            'send_email_receipt' => true, // Enable email receipt
            'show_line_items' => true,
            'cancel_url' => 'http://localhost/woodworks/product_details.php?product_id=' . $product_id,
            'line_items' => $cart_items,
            'success_url' => 'http://localhost/woodworks/transaction/success2.php?payment_success=1',
            'payment_method_types' => ['gcash', 'paymaya'],
            'metadata' => [
                'user_id' => $user_id,
                'full_name' => $full_name,
                'product_name' => $product_name,
                'product_price' => $product_price,
                'quantity' => $quantity,
                'address' => $user_address,
                'mobile_number' => $user_contact,
                'payment_method' => 'PayMongo',
                'created_at' => date('c'),
            ],
        ],
    ],
];

try {
    // Initiate PayMongo checkout session
    $client = new Client();
    $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
        'body' => json_encode($body),
        'headers' => [
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => 'Basic ' . base64_encode(''),
        ],
    ]);

    $checkoutSession = json_decode($response->getBody(), true);

    // Store cart items in session to pass to success2.php
    $_SESSION['checkout_items'] = $cart_items;
    $_SESSION['delivery_option'] = $delivery_option . '/' . $delivery_fee;
    $_SESSION['user_address'] = $user_address; // Save retrieved user address
    $_SESSION['payment_method'] = 'PayMongo'; // Save payment method


    // Return the checkout URL for redirection to PayMongo
    header('Location: ' . $checkoutSession['data']['attributes']['checkout_url']);
    exit();

} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}

?>
