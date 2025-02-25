<?php
session_start(); // Start the session


// Check if the user is logged in as admin
if (!isset($_SESSION['admin_username'])) {
    // Redirect to login page if not logged in as admin
    header("Location: ../user/login.php");
    exit;
}

// Check if the order ID is provided
if (!isset($_POST['order_id'])) {
    // Redirect back to the previous page or any desired page
    header("Location: previous_page.php");
    exit;
}

// Retrieve the order ID from the POST data
$order_id = $_POST['order_id'];

include('../includes/connection.php'); 

// Check if the form is submitted
if (isset($_POST['submit_delivery_date'])) {
    // Retrieve the selected delivery date from the form
    $delivery_date = $_POST['delivery_date'];

    // Update the order with the selected delivery date in the database
    $update_query = "UPDATE orders SET delivery_date = '$delivery_date' WHERE order_id = $order_id";
    $result = mysqli_query($con, $update_query);

    if ($result) {
        // Delivery date updated successfully
        // Redirect back to the previous page or any desired page
        header("Location: previous_page.php");
        exit;
    } else {
        // Error occurred while updating the delivery date
        echo "Error: " . mysqli_error($con);
    }
}

// Fetch the order details from the database based on the provided order ID
$select_query = "SELECT * FROM orders WHERE order_id = $order_id";
$result = mysqli_query($con, $select_query);

if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the order details
    $row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Delivery Date</title>
</head>
<body>
    <h2>Select Delivery Date</h2>
    <form method="post">
        <label for="delivery_date">Select Delivery Date:</label>
        <input type="date" id="delivery_date" name="delivery_date" required>
        <input type="submit" name="submit_delivery_date" value="Submit">
    </form>
</body>
</html>

<?php
} else {
    // No order found with the provided order ID
    echo "Order not found.";
}
?>
