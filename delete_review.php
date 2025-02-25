<?php 
include __DIR__ . '/includes/connection.php';
session_start();
// Check if the review_id is set in the GET request
if (isset($_GET['review_id']) && isset($_GET['product_id'])) {
    $review_id = intval($_GET['review_id']); // Sanitize review_id
    $product_id = intval($_GET['product_id']); // Sanitize product_id

    // Prepare the delete statement
    $stmt = $con->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $review_id); // Bind the review_id to the statement

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the product details page with a success message
        header("Location: product_details.php?product_id=$product_id#reviewSection&delete_success=1");
        exit;
    } else {
        // Handle error
        echo "Error deleting review: " . $stmt->error;
    }

    $stmt->close(); // Close the statement
} else {
    // Redirect back to the product details page if no review_id is provided
    header("Location: product_details.php?product_id=" . intval($_GET['product_id']));
    exit;
}
?>

?>