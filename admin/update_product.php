<?php
include('../includes/connection.php');

if (isset($_POST['update_product'])) {
    // Retrieve the product data from the form
    $product_id = (int)$_POST['product_id'];
    $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
    $product_description = mysqli_real_escape_string($con, $_POST['product_description']);
    $product_description2 = mysqli_real_escape_string($con, $_POST['product_description2']);
    $product_keyword = mysqli_real_escape_string($con, $_POST['product_keyword']);
    $product_category = mysqli_real_escape_string($con, $_POST['product_category']);
    $product_price = mysqli_real_escape_string($con, $_POST['product_price']);
    $promo = mysqli_real_escape_string($con, $_POST['promo']); // Get the promo value

    // Handle file uploads if any images are provided
    $product_image1 = !empty($_FILES['product_image1']['name']) ? $_FILES['product_image1']['name'] : null;
    $product_image2 = !empty($_FILES['product_image2']['name']) ? $_FILES['product_image2']['name'] : null;
    $product_image3 = !empty($_FILES['product_image3']['name']) ? $_FILES['product_image3']['name'] : null;
    $product_image4 = !empty($_FILES['product_image4']['name']) ? $_FILES['product_image4']['name'] : null;

    // If an image is uploaded, move it to the target directory
    if ($product_image1) move_uploaded_file($_FILES['product_image1']['tmp_name'], "product_images/$product_image1");
    if ($product_image2) move_uploaded_file($_FILES['product_image2']['tmp_name'], "product_images/$product_image2");
    if ($product_image3) move_uploaded_file($_FILES['product_image3']['tmp_name'], "product_images/$product_image3");
    if ($product_image4) move_uploaded_file($_FILES['product_image4']['tmp_name'], "product_images/$product_image4");

    // Build the update query
    $query = "UPDATE products SET 
                product_name = '$product_name', 
                product_description = '$product_description', 
                product_description2 = '$product_description2',
                product_keyword = '$product_keyword', 
                category_title = '$product_category', 
                product_price = '$product_price', 
                promo = '$promo'"; // Add promo to the update query

    // Add image fields to query if new images are uploaded
    if ($product_image1) $query .= ", product_image1 = '$product_image1'";
    if ($product_image2) $query .= ", product_image2 = '$product_image2'";
    if ($product_image3) $query .= ", product_image3 = '$product_image3'";
    if ($product_image4) $query .= ", product_image4 = '$product_image4'";

    $query .= " WHERE product_id = $product_id";

    // Execute the query
    if (mysqli_query($con, $query)) {
        if (mysqli_affected_rows($con) > 0) {
            // Redirect back to the product page after successful update
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // If no changes are made, output a message
            echo "No changes made to the product.";
        }
    } else {
        // If there is an error executing the query, output the error message
        echo "Error updating product: " . mysqli_error($con);
    }
}
?>
