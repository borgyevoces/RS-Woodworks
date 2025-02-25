<?php
include('../includes/connection.php'); 
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $query = "SELECT * FROM products WHERE product_id = '$product_id'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        // Set default for promo if not set or null
        if (!isset($product['promo']) || is_null($product['promo'])) {
            $product['promo'] = '';
        }
        echo json_encode($product); // Return the product data as JSON
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
}
?>
