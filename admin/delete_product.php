<?php
include('../includes/connection.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Fetch product details to delete associated images
    $query = "SELECT product_image1, product_image2, product_image3, product_image4 FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Delete product images if they exist
        foreach (['product_image1', 'product_image2', 'product_image3', 'product_image4'] as $image_field) {
            if (!empty($product[$image_field]) && file_exists("product_images/" . $product[$image_field])) {
                unlink("product_images/" . $product[$image_field]); // Delete the image file
            }
        }

        // Delete product from the database
        $delete_query = "DELETE FROM products WHERE product_id = ?";
        $delete_stmt = mysqli_prepare($con, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, 'i', $product_id);
        if (mysqli_stmt_execute($delete_stmt)) {
            // Redirect to the referring page
            $referer = $_SERVER['HTTP_REFERER'] ?? 'products.php'; // Fallback to 'products.php' if HTTP_REFERER is not set
            header("Location: $referer");
            exit();
        } else {
            echo "Error deleting product: " . mysqli_error($con);
        }
    } else {
        echo "Product not found.";
    }
} else {
    echo "Invalid request.";
}

?>
