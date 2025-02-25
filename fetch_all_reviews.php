<?php
include __DIR__ . '/includes/connection.php';
session_start();


if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']); // Sanitize the product ID

    // Fetch all reviews for the given product
    $reviews_query = "SELECT reviews.*, user_table.username, user_table.user_image 
                      FROM reviews 
                      JOIN user_table ON reviews.user_id = user_table.user_id 
                      WHERE reviews.product_id = $product_id 
                      ORDER BY reviews.created_at DESC";

    $reviews_result = mysqli_query($con, $reviews_query);

    if (!$reviews_result) {
        die('Error fetching reviews: ' . mysqli_error($con));
    }

    if (mysqli_num_rows($reviews_result) > 0) {
        while ($review = mysqli_fetch_assoc($reviews_result)) {
            $username = htmlspecialchars($review['username']);
            $user_image = !empty($review['user_image']) ? htmlspecialchars($review['user_image']) : './user/user_images/defaultuser.png';
            $rating = intval($review['rating']);
            $review_text = htmlspecialchars($review['review']);
            $created_at = htmlspecialchars($review['created_at']);
            $review_id = $review['id'];

            echo "
            <div class='single-review'>
                <div class='review-header'>
                    <div class='review-user-info'>
                        <img src='./user/$user_image' alt='$username' class='review-user-image'>
                        <strong class='review-username'>$username</strong>
                    </div>
                    <div class='review-rating'>" . str_repeat('★', $rating) . "</div>
                </div>
                <p class='review-text'>$review_text</p>
                <small class='review-date'>Reviewed on: $created_at</small>";

            // Add edit and delete buttons if the user is logged in
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']) {
                echo "
                <div class='review-actions'>
                    <button class='edit-review-btn' data-review-id='$review_id' data-review-text='$review_text'>Edit Review</button>
                    <button class='delete-review-btn' data-review-id='$review_id'>Delete</button>
                </div>";
            }

            echo "</div>";
        }
    } else {
        echo "<p>No more reviews available.</p>";
    }
} else {
    echo "<p>Invalid product ID.</p>";
}
?>
