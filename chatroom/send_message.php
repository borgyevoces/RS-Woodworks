<?php
session_start();
$con = new mysqli('localhost', 'root', '', 'rswoodworks');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$user_id = $_SESSION['user_id'] ?? 0;
$is_admin = $_SESSION['is_admin'] ?? false;
$admin_id = $_SESSION['admin_id'] ?? 1; // Assuming you have a way to get the admin ID

if (isset($_POST['message']) && $user_id) {
    $message = trim($_POST['message']);

    // Automated replies for specific FAQ messages
    $faqResponses = [
        "What are your business hours?" => "Our website is open 24/7 you can order our beloved products anytime.",
        "How can I contact support?" => "You can contact support via this chat or by email at rswoodworks@gmail.com.",
        "What is your return policy?" => "We offer a 14-days return policy if the products are broken or damaged upon delivery.",
        "Do you offer international shipping?" => "We only offer local shipping around CALABARZON and Metro Manila.",
        "Where can I find the tracking information?" => "We will update you about the status of your shipment on our website notification and via email or text message ."
    ];

    // Insert the user's message into the database
    $receiver_id = NULL; // No specific admin ID for user messages
    $receiver_type = 'admin';
    $sender_type = 'user'; // Set sender type to user

    // Insert the user's message into the database
    $query = "INSERT INTO messages (sender_id, receiver_id, receiver_type, sender_type, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("iisss", $user_id, $receiver_id, $receiver_type, $sender_type, $message);
    $stmt->execute();

    // Check if the message matches any FAQ for automated response
    if (array_key_exists($message, $faqResponses)) {
        // Prepare the automated response
        $autoReply = $faqResponses[$message];

        // Set sender and receiver details for the automated reply
        $sender_id = $admin_id; // The automated reply comes from the admin
        $receiver_id = $user_id; // The user who sent the original message
        $receiver_type = 'user'; // Set receiver type to user
        $sender_type = 'admin'; // Set sender type to admin for automated response

        // Insert the automated response into the database
        $autoReplyQuery = "INSERT INTO messages (sender_id, receiver_id, receiver_type, sender_type, message) VALUES (?, ?, ?, ?, ?)";
        $autoStmt = $con->prepare($autoReplyQuery);
        $autoStmt->bind_param("iisss", $sender_id, $receiver_id, $receiver_type, $sender_type, $autoReply);
        $autoStmt->execute();
        $autoStmt->close();
    }

    // Close the user message statement
    $stmt->close();
    echo "User message sent successfully. " . (isset($autoReply) ? "Automated response sent." : "No automated response available.");
} else {
    echo "Invalid request.";
}

$con->close();
?>
