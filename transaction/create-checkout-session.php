<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

include('../includes/connection.php'); 
// Initialize variables
$overallSubtotal = 0;
$cart_items = [];

// Fetch user email and contact
$user_id = $_SESSION['user_id'];
$user_query = "SELECT user_email, user_contact FROM user_table WHERE user_id = ?";
$user_stmt = $con->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_stmt->bind_result($user_email, $user_contact);
$user_stmt->fetch();
$user_stmt->close();

// Fetch user address and full name from the user_table based on user_id
$user_address_query = "SELECT user_address, full_name FROM user_table WHERE user_id = ?";
$user_address_stmt = $con->prepare($user_address_query);
$user_address_stmt->bind_param("i", $user_id);
$user_address_stmt->execute();
$user_address_stmt->bind_result($user_address, $full_name);
$user_address_stmt->fetch();
$user_address_stmt->close();

// Fetch cart items from the database
$cart_query = "SELECT cd.quantity, p.product_price, p.product_name FROM cart_details cd 
               JOIN products p ON cd.product_id = p.product_id 
               WHERE cd.user_id = ?";
$stmt = $con->prepare($cart_query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are cart items
if ($result->num_rows === 0) {
    echo json_encode(['error' => 'No items in cart.']);
    exit();
}

// Process cart items
while ($row = $result->fetch_assoc()) {
    // Validate product price and quantity
    if ($row['product_price'] > 0 && $row['quantity'] > 0) {
        $itemSubtotal = $row['product_price'] * $row['quantity'];
        $overallSubtotal += $itemSubtotal;

        // Prepare line item for PayMongo in the simplified format
        $cart_items[] = [
            'currency' => 'PHP', // Correct currency code
            'amount' => (int)($row['product_price'] * 100), // Amount in cents
            'name' => $row['product_name'] ?? 'Unknown Product',
            'quantity' => (int)$row['quantity'],
        ];
    } else {
        echo "Invalid product data: Price - {$row['product_price']}, Quantity - {$row['quantity']}";
    }
}

// Get delivery fee from the form data
$delivery_fee = isset($_POST['delivery']) ? (int)$_POST['delivery'] : 0;

// Calculate total amount
$total_amount = $overallSubtotal + $delivery_fee; // Total in pesos

// Convert total amount to cents for validation
$total_amount_in_cents = $total_amount * 100;

// Check if total amount meets the minimum requirement
if ($total_amount_in_cents < 2000) { // 2000 cents is ₱20.00
    echo json_encode(['error' => 'Total amount must be at least ₱20.00 to proceed with checkout.']);
    exit();
}

// Add delivery fee as a line item if applicable
if ($delivery_fee > 0) {
    $cart_items[] = [
        'currency' => 'PHP', // Correct currency code
        'amount' => (int)($delivery_fee * 100), // Delivery fee in cents
        'name' => 'Delivery Fee',
        'quantity' => 1,
    ];
}

// Save delivery option to session
$delivery_option = $_POST['delivery'] == 120 ? 'Standard/120' : 'Truck/400';
$_SESSION['delivery_option'] = $delivery_option;

// Debugging output to check line items
if (empty($cart_items)) {
    echo json_encode(['error' => 'No valid items to checkout.']);
    exit();
}

// Prepare the request body for PayMongo
$body = [
    'data' => [
        'attributes' => [
            'send_email_receipt' => true, // Enable email receipt
            'show_line_items' => true,
            'cancel_url' => 'http://localhost/woodworks/cart.php', // Adjust to your URL
            'line_items' => $cart_items,
            'success_url' => 'http://localhost/woodworks/transaction/success.php?payment_success=1&user_email=' . urlencode($user_email) . '&user_contact=' . urlencode($user_contact), // Pass email and contact in the success URL
            'payment_method_types' => ['gcash', 'paymaya'], // Adjust as necessary
            'metadata' => [
                'user_id' => $user_id, // Store user ID in metadata
                'full_name' => $full_name, // Store user's full name in metadata
                'address' => $user_address,
                'mobile_number' => $user_contact,
                'payment_method' => 'PayMongo', // Store payment method in metadata
                // Add product details to metadata
                'products' => array_map(function($item) {
                    return [
                        'product_name' => $item['name'],
                        'product_price' => $item['amount'] / 100,
                        'quantity' => $item['quantity'],
                    ];
                }, $cart_items),
                'created_at' => date('c'), // ISO 8601 format for the current timestamp
            ],
        ],
    ],
];

try {
    $client = new Client();
    
    // Make the API request
    $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
        'body' => json_encode($body),
        'headers' => [
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => 'Basic ' . base64_encode(''), // Your actual API key
        ],
    ]);

    $checkoutSession = json_decode($response->getBody(), true);

    // Redirect to PayMongo checkout URL
    header('Location: ' . $checkoutSession['data']['attributes']['checkout_url']);
    exit;

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
