<?php
session_start(); // Start the session to use session variables
include('../includes/connection.php'); 

// update_image.php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['user_image'])) {
    $user_id = $_SESSION['user_id']; // Assuming user is logged in and user_id is stored in session

    $image = $_FILES['user_image'];
    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];

    // Validate the image (e.g., check file type and size)
    if ($image_error === 0) {
        if ($image_size <= 5000000) { // Max file size 5MB
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($image_ext, $allowed_exts)) {
                $new_image_name = uniqid('', true) . '.' . $image_ext;

                // Ensure the directory exists
                $image_dest_dir = './user_images/';
                if (!is_dir($image_dest_dir)) {
                    mkdir($image_dest_dir, 0777, true); // Create the directory with appropriate permissions
                }

                $image_dest = $image_dest_dir . $new_image_name;

                if (move_uploaded_file($image_tmp_name, $image_dest)) {
                    // Update the user's image in the database
                    $query = "UPDATE user_table SET user_image = ? WHERE user_id = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param('si', $image_dest, $user_id);
                    if ($stmt->execute()) {
                        $_SESSION['user_image'] = $image_dest; // Update session with new image path
                        header('Location: profile.php?success=Image updated');
                    } else {
                        echo "Error updating profile image.";
                    }
                } else {
                    echo "Error uploading file.";
                }
            } else {
                echo "Invalid file type.";
            }
        } else {
            echo "File size is too large.";
        }
    } else {
        echo "Error with file upload.";
    }
}
?>
