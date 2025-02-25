<?php
require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

function createPayMongoCheckoutSession($amount, $currency, $description) {
    $client = new Client();
    $apiKey = ''; // Replace with your PayMongo secret key

    try {
        $response = $client->request('POST', 'https://api.paymongo.com/v1/links', [
            'auth' => [$apiKey, ''],
            'json' => [
                'data' => [
                    'attributes' => [
                        'amount' => $amount,
                        'description' => $description,
                        'remarks' => 'Order Payment',
                        'redirect' => [
                            'success' => 'http://yourwebsite.com/success',
                            'failed' => 'http://yourwebsite.com/failed'
                        ],
                        'currency' => $currency
                    ]
                ]
            ]
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['data']['attributes']['checkout_url'];
    } catch (Exception $e) {
        error_log('Error creating PayMongo checkout session: ' . $e->getMessage());
        error_log('Response: ' . $e->getResponse()->getBody()->getContents());
        return null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $currency = 'PHP';
    $description = 'Custom Palochina Bench';

    $checkoutUrl = createPayMongoCheckoutSession($amount, $currency, $description);

    if ($checkoutUrl) {
        header('Location: ' . $checkoutUrl);
        exit();
    } else {
        echo 'Error creating payment session. Please try again.';
    }
}
?>
