<?php
require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login2.php");
    exit();
}

try {
    $client = new Client();
    $response = $client->request('GET', 'https://api.paymongo.com/v1/payments', [
        'headers' => [
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => 'Basic ' . base64_encode(''), // Your actual API key
        ],
    ]);

    $payments = json_decode($response->getBody(), true);

    if (isset($payments['data']) && !empty($payments['data'])) {
        echo "<h1>Payments</h1>";
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Created At</th>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Quantity</th>
                    <th>Address</th>
                    <th>Mobile Number</th>
                    <th>Payment Method</th>
                </tr>";

        foreach ($payments['data'] as $payment) {
            $metadata = $payment['attributes']['metadata'] ?? [];
            $user_id = $metadata['user_id'] ?? 'Unknown';
            $full_name = $metadata['full_name'] ?? 'Unknown';
            $product_name = $metadata['product_name'] ?? 'Unknown';
            $product_price = $metadata['product_price'] ?? 'Unknown';
            $quantity = $metadata['quantity'] ?? 'Unknown';
            $address = $metadata['address'] ?? 'Unknown';
            $mobile_number = $metadata['mobile_number'] ?? 'Unknown';
            $payment_method = $payment['attributes']['source']['type'] ?? 'Unknown'; // Get the payment method from the source type

            // Convert the created_at timestamp to a human-readable format
            $created_at = date('Y-m-d H:i:s', strtotime($payment['attributes']['created_at']));

            echo "<tr>
                    <td>{$payment['id']}</td>
                    <td>{$payment['attributes']['status']}</td>
                    <td>" . ($payment['attributes']['amount'] / 100) . "</td>
                    <td>{$payment['attributes']['currency']}</td>
                    <td>{$created_at}</td>
                    <td>{$user_id}</td>
                    <td>{$full_name}</td>
                    <td>{$product_name}</td>
                    <td>{$product_price}</td>
                    <td>{$quantity}</td>
                    <td>{$address}</td>
                    <td>{$mobile_number}</td>
                    <td>{$payment_method}</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No payments found.</p>";
    }

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
