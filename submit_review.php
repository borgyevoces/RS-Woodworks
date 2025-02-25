<?php
include __DIR__ . '/includes/connection.php';
session_start();

// Check if required data is present
if (isset($_POST['rating']) && isset($_POST['review']) && isset($_POST['product_id'])) {
    $rating = intval($_POST['rating']);
    $review_text = htmlspecialchars($_POST['review']);
    $product_id = intval($_POST['product_id']);
    $user_id = intval($_SESSION['user_id']);

    // Initialize file paths as null
    $image_path = null;
    $video_path = null;

    // Handle media upload
    if (isset($_FILES['review_media']) && !empty($_FILES['review_media']['name'][0])) {
        // Loop through each uploaded file
        foreach ($_FILES['review_media']['name'] as $key => $name) {
            $file_tmp = $_FILES['review_media']['tmp_name'][$key];
            $file_error = $_FILES['review_media']['error'][$key];

            if ($file_error == UPLOAD_ERR_OK) {
                // Determine file type
                $file_extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                
                // Create a unique file path
                $file_path = 'media/reviews_media/' . uniqid() . '-' . basename($name);

                // Move the uploaded file to the destination
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Separate image and video paths for storage
                    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $image_path = $file_path; // Store the image path
                    } elseif (in_array($file_extension, ['mp4', 'mov', 'avi', 'wmv'])) {
                        $video_path = $file_path; // Store the video path
                    }
                } else {
                    echo "Error uploading file: $name";
                    exit;
                }
            }
        }
    }

    // Set paths to null if no media was uploaded
    if ($image_path === null) {
        $image_path = null; // Set to null if no image was uploaded
    }
    if ($video_path === null) {
        $video_path = null; // Set to null if no video was uploaded
    }

    // Insert the review, along with file paths for image and video, into the database
    $stmt = $con->prepare("INSERT INTO reviews (user_id, product_id, rating, review, image_path, video_path, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    
    // Bind parameters (ensuring the image_path and video_path are nullable)
    $stmt->bind_param("iiisss", $user_id, $product_id, $rating, $review_text, $image_path, $video_path);

    if ($stmt->execute()) {
        // Redirect back to product page after success
        header("Location: product_details.php?product_id=$product_id#reviewSection");
        exit; // Ensure to exit after header redirect
    } else {
        echo "Error: " . $con->error;
    }
} else {
    echo "All fields are required.";
}
?>
