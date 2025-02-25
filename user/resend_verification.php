
<?php
require __DIR__ . '/../vendor/autoload.php';
use Kreait\Firebase\Factory;

header('Content-Type: application/json');

$firebase = (new Factory)
    ->withServiceAccount(__DIR__ . '/../config/firebase-admin-sdk.json')
    ->createAuth();

$auth = $firebase;

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email'])) {
    $email = $data['email'];

    try {
        $auth->sendEmailVerificationLink($email);
        echo json_encode(['success' => true]);
    } catch (\Kreait\Firebase\Exception\AuthException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } catch (\Throwable $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>