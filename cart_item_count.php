<?php
session_start();
include __DIR__ . '/includes/connection.php';

function cart_item(){
    global $con;
    
    // Check if user is logged in
    if(isset($_SESSION['user_id'])) {
        // Get the logged-in user's ID
        $user_id = $_SESSION['user_id'];
        
        // Query to check items in the user's cart
        $select_query = "SELECT * FROM `cart_details` WHERE user_id='$user_id'";
        $result_query = mysqli_query($con, $select_query);
        $count_cart_items = mysqli_num_rows($result_query);
        
        // Return the count of cart items
        echo $count_cart_items;
    } else {
        // If user is not logged in, return 0 cart items
        echo 0;
    }
}

// Call the function to output the cart count
cart_item();
?>
