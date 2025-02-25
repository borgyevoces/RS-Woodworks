<?php
// Include your database connection file here
$con = mysqli_connect('localhost', 'root', '', 'rswoodworks');

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["order_received"])) {
    // Start the session
    session_start();

    // Check if the user is logged in and has a valid user ID stored in the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Fetch user details from user_table
        $sql_fetch_user = "SELECT full_name, user_email, user_address FROM user_table WHERE user_id = ?";
        $stmt_fetch_user = mysqli_prepare($con, $sql_fetch_user);
        mysqli_stmt_bind_param($stmt_fetch_user, "i", $user_id);
        mysqli_stmt_execute($stmt_fetch_user);
        mysqli_stmt_bind_result($stmt_fetch_user, $full_name, $user_email, $user_address);
        mysqli_stmt_fetch($stmt_fetch_user);
        mysqli_stmt_close($stmt_fetch_user);

        // Get the form data
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_image = $_POST['product_image'];
        $product_price = $_POST['product_price'];
        $quantity = $_POST['quantity'];
        $payment_status = ""; // Fetch from orders or shipped_orders based on your logic
        $date_received = date("Y-m-d"); // Current date
        $status = "completed";

        // Fetch payment_status from orders or shipped_orders table
        $sql_fetch_payment_status = "SELECT payment_status FROM shipped_orders WHERE product_id = ?"; // Change table name accordingly
        $stmt_fetch_payment_status = mysqli_prepare($con, $sql_fetch_payment_status);
        mysqli_stmt_bind_param($stmt_fetch_payment_status, "i", $product_id);
        mysqli_stmt_execute($stmt_fetch_payment_status);
        mysqli_stmt_bind_result($stmt_fetch_payment_status, $payment_status);
        mysqli_stmt_fetch($stmt_fetch_payment_status);
        mysqli_stmt_close($stmt_fetch_payment_status);

        // Insert the order information into the completed_order table
        $sql = "INSERT INTO completed_order (user_id, full_name, user_email, product_id, product_name, product_image, product_price, quantity, payment_status, user_address, date_received, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "isssssssssss", $user_id, $full_name, $user_email, $product_id, $product_name, $product_image, $product_price, $quantity, $payment_status, $user_address, $date_received, $status);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Delete the order from the shipped_orders table
            $delete_sql = "DELETE FROM shipped_orders WHERE product_id = ?";
            $delete_stmt = mysqli_prepare($con, $delete_sql);
            mysqli_stmt_bind_param($delete_stmt, "i", $product_id);
            mysqli_stmt_execute($delete_stmt);
            mysqli_stmt_close($delete_stmt);

            // Redirect back to profile.php
            header("Location: profile.php?order_received_success=true");
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle the case where the user is not logged in or does not have a valid user ID
        echo "User is not logged in.";
    }
} else {
    echo "Form not submitted.";
}

// Close the connection
mysqli_close($con);
?>
