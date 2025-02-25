<?php
include __DIR__ . '/includes/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = intval($_POST['review_id']);
    $rating = intval($_POST['rating']);
    $review_text = mysqli_real_escape_string($con, $_POST['review']);
    
    // Ensure the user is logged in and is the owner of the review
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Update the review
        $update_query = "UPDATE reviews SET rating = '$rating', review = '$review_text' WHERE id = '$review_id' AND user_id = '$user_id'";
        if (mysqli_query($con, $update_query)) {
            // Redirect back to the referring page
            $referer = $_SERVER['HTTP_REFERER'] ?? 'product_details.php'; // Fallback URL if HTTP_REFERER is not set
            header("Location: $referer#reviewSection");
            exit;
        } else {
            echo "Error updating review.";
        }
    }
}
?>

