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
    $response = $client->request('GET', 'https://api.paymongo.com/v1/checkout_sessions', [
        'headers' => [
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => 'Basic ' . base64_encode(''), // Your actual API key
        ],
    ]);

    $checkoutSessions = json_decode($response->getBody(), true);

    if (isset($checkoutSessions['data']) && !empty($checkoutSessions['data'])) {
        echo "<h1>Checkout Sessions</h1>";
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Created At</th>
                </tr>";

        foreach ($checkoutSessions['data'] as $session) {
            echo "<tr>
                    <td>{$session['id']}</td>
                    <td>{$session['attributes']['status']}</td>
                    <td>" . ($session['attributes']['amount'] / 100) . "</td>
                    <td>{$session['attributes']['currency']}</td>
                    <td>" . date('Y-m-d H:i:s', strtotime($session['attributes']['created_at'])) . "</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No checkout sessions found.</p>";
    }

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
