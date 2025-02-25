<?php include 'includes/header.php'; ?>

<!-- Success popup container -->
<div id="success-popup" class="success-popup" style="display: none;">
    <span class="close-btn">&times;</span>
    <div class="popup-complete">
        <div class="image-container">
            <img src="media/order-confirmed.svg" alt="Success Image">
        </div>
        <p>Order Successful</p>
        <a href="user/profile.php">Order List</a>
    </div>
</div>

<!-- JavaScript for the success popup -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'true') {
        document.getElementById('success-popup').style.display = 'block';
    }
    document.querySelector('.success-popup .close-btn').addEventListener('click', function() {
        document.getElementById('success-popup').style.display = 'none';
    });
});
</script>
<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<div class='empty-cart'>
        <img src='media/cartempty.svg' alt='Empty Cart Image'>
        <h1>Looks like you have not added anything to your cart. <br> Go ahead and explore our store!</h1>
        <div class='button-container'>
          <a href='product.php'><button>Shop Now</button></a>
        </div>
      </div>";
    exit();
}

// Fetch cart items for the logged-in user
$user_id = $_SESSION['user_id'];  // Get user_id from session
$cart_query = "SELECT * FROM cart_details WHERE user_id = $user_id";
$result = mysqli_query($con, $cart_query);

if (mysqli_num_rows($result) === 0) {
    echo "<div class='empty-cart'>
        <img src='media/cartempty.svg' alt='Empty Cart Image'>
        <h1>Looks like you have not added anything to your cart. <br> Go ahead and explore our store!</h1>
        <div class='button-container'>
          <a href='product.php'><button>Shop</button></a>
        </div>
      </div>";
    exit();
}

// Update cart items quantity
function update_cart_quantity($con) {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'quantity_') !== false) {
                $product_id = intval(substr($key, strlen('quantity_')));
                $quantity = max(1, intval($value)); // Set minimum quantity to 1
                mysqli_query($con, "UPDATE cart_details SET quantity = $quantity WHERE product_id = $product_id");
            }
        }
    }
}

update_cart_quantity($con);

// Fetch cart items with product details for the logged-in user
$query = "SELECT cd.product_id, p.product_name, p.product_description, p.product_description2, 
                 p.product_price, cd.quantity, p.product_image1 
          FROM cart_details cd 
          INNER JOIN products p ON cd.product_id = p.product_id 
          WHERE cd.user_id = $user_id";
$result = mysqli_query($con, $query);

$overallSubtotal = 0;

// Add this line to ensure the hidden amount input field gets updated
echo "<input type='hidden' name='amount' value='" . ($overallSubtotal * 100) . "'>"; // Update amount value in cents
?>

<h2 class="shopping-cart-title">
    <i class="fa-solid fa-cart-shopping"></i> Your Shopping Cart
</h2>

<style>
    .shopping-cart-title {
        margin: 20px auto;
        font-weight: 400;
        font-size: 30px;
        text-align: left;
        color: #333;
        margin-left: 180px;
   
    }

    @media (max-width: 768px) {
        .shopping-cart-title {
            font-size: 17px;
            margin: 20px auto;
            text-align: center;
        }
        
    }
</style>
<form id="checkout-form" method="post" action="./transaction/create-checkout-session.php">
    <input type="hidden" name="amount" value="<?php echo $overallSubtotal * 100; ?>"> <!-- PHP to convert to cents -->
    <input type="hidden" id="deliveryOptionInput" name="delivery_option" value="Standard/120">
    <input type="hidden" id="paymentMethodInput" name="payment_method" value="PayMongo">
    <div class="container" style="margin-top: 50px; margin-bottom: 50px;">
        <!-- Cart Items Section -->
        <div id="shopping-cart" class="cart-items">
            <?php 
            $overallSubtotal = 0;
            while ($row = mysqli_fetch_assoc($result)): 
                $itemSubtotal = $row['product_price'] * $row['quantity'];
                $overallSubtotal += $itemSubtotal; 
            ?>
                <div class="cart-item" data-product-id="<?php echo $row['product_id']; ?>">
                    <img src="admin/product_images/<?php echo $row['product_image1']; ?>" alt="Product Image" class="item-image">
                    <div class="item-info">
                        <h3 class="item-name"><?php echo $row['product_name']; ?></h3>
                        <p class="item-description"><?php echo $row['product_description2'] . "<br>" . $row['product_description']; ?></p>
                        <p class="item-price">Price: ₱<?php echo number_format($row['product_price'], 2); ?></p>
                        <p class="item-subtotal">Subtotal: ₱<?php echo number_format($itemSubtotal, 2); ?></p>
                        <div class="quantity">
                            <button class="minus"><i class="fas fa-minus"></i></button>
                            <input type="number" name="quantity_<?php echo $row['product_id']; ?>" value="<?php echo $row['quantity']; ?>">
                            <button class="plus"><i class="fas fa-plus"></i></button>
                            <button class="remove-btn" name="removeitem[]" value="<?php echo $row['product_id']; ?>">Remove</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Fetch user's address -->
        <?php
        $user_address_query = "SELECT user_address FROM user_table WHERE user_id = $user_id";
        $user_address_result = mysqli_query($con, $user_address_query);

        if ($user_address_result && mysqli_num_rows($user_address_result) > 0) {
            $user_address_row = mysqli_fetch_assoc($user_address_result);
            $user_address = $user_address_row['user_address']; 
        } else {
            $user_address = null; 
        }
        ?>

        <!-- Checkout Summary Section -->
        <div class="checkout-summary">
            <h2 style="font-weight: bold;">Checkout Summary</h2>
            <div class="delivery-address">
                <h2 style="font-weight: bold;">Delivery Address</h2>
                <div class="current-address">
                    <h3><i class="bi bi-geo-alt"></i> Current Address:</h3>
                    <p style="font-size: 12px;"><?php echo isset($user_address) ? $user_address : "Address not available"; ?></p>
                </div>
                <div class="change-address">
                    <a href="./user/profile.php" style="padding: 8px 10px; background-color: #1976D2; color: #fff; border-radius: 5px;">Change</a>
                </div>
            </div>
            <hr style="border: 1px solid #333; margin: 10px 0;">

            <div class="delivery-options">
                <h5 style="font-weight: bold; color: #333;">
                    <i class="fa-solid fa-truck"></i> Delivery Options
                </h5>
                <label class="delivery-option">
                    <input type="radio" name="delivery" value="120" onchange="updateTotalAmount();" checked>
                    <span class="option-text">Standard Delivery</span>
                    <span class="option-details">3-5 Days</span>
                    <span class="option-price">₱120.00</span>
                </label>
                <label class="delivery-option">
                    <input type="radio" name="delivery" value="400" onchange="updateTotalAmount();">
                    <span class="option-text">Truck Delivery</span>
                    <span class="option-details">1-3 Days</span>
                    <span class="option-price">₱400.00</span>
                </label>
            </div>

            <div class="payment-methods">
                <h5 style="font-weight: bold; color: #333;">
                    <i class="fa-solid fa-credit-card"></i> Payment Methods
                </h5>
                <label class="payment-method" style="align-items: left; padding: 10px 0;border-bottom: 1px solid #ddd;">
                    <input type="radio" name="payment_method" value="PayMongo" onchange="updatePaymentMethod();" checked>
                    <span class="option-text"><img src="media/payment-method.png" alt="Pay Online" style="width: 25px; height: 25px;  margin-left: 10px;" > Pay Online</span>
                </label>
                <label class="payment-method" style="align-items: left; padding: 10px 0;border-bottom: 1px solid #ddd;">
                    <input type="radio" name="payment_method" value="CashOnDelivery" onchange="updatePaymentMethod();">
                    <span class="option-text"><img src="media/fast-delivery.png" alt="Cash on Delivery" style="width: 25px; height: 25px;  margin-left: 10px;"> Cash on Delivery</span>
                </label>
            </div>

            <h2 style="font-weight: bold;">Overall Total </h2>
            <div class="subtotal">
                <span id="subtotal">Subtotal: ₱<?php echo number_format($overallSubtotal, 2); ?></span>
            </div>
            <div class="delivery-fee">
                <span id="delivery-fee">Delivery Fee: ₱<span id="delivery-cost">0.00</span></span>
            </div>
            <div class="total-amount">
                <strong>Total Amount: ₱<span id="total-amount"><?php echo number_format($overallSubtotal, 2); ?></span></strong>
            </div>

            <div class='check-out-btn'>
                <button type='button' id='checkout-btn' onclick='initiateCheckout();' style="background-color: royalblue; color: white;">
                    <?php echo isset($_SESSION['username']) ? 'Check Out' : 'Login to Checkout'; ?>
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Function to update total amount based on delivery option
function updateTotalAmount() {
    const deliveryFee = document.querySelector('input[name="delivery"]:checked')?.value || 0;
    const subtotal = <?php echo $overallSubtotal; ?>;
    const totalAmount = subtotal + parseInt(deliveryFee);
    document.getElementById('delivery-cost').textContent = parseFloat(deliveryFee).toFixed(2);
    document.getElementById('total-amount').textContent = totalAmount.toFixed(2);
    let deliveryOption = document.querySelector('input[name="delivery"]:checked').nextElementSibling.textContent;
    document.getElementById('deliveryOptionInput').value = deliveryOption + '/' + deliveryFee;
}

function updatePaymentMethod() {
    let paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    document.getElementById('paymentMethodInput').value = paymentMethod;
}

function initiateCheckout() {
    const userAddress = "<?php echo $user_address; ?>";
    if (!userAddress) {
        document.getElementById('address-modal').style.display = 'block';
        return;
    }

    const paymentMethod = document.getElementById('paymentMethodInput').value;
    if (paymentMethod === 'CashOnDelivery') {
        document.getElementById('checkout-form').action = './transaction/cash_on_delivery_cart.php';
    } else {
        document.getElementById('checkout-form').action = './transaction/create-checkout-session.php';
    }
    const deliveryFee = parseFloat(document.getElementById('delivery-cost').textContent);
    const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace('Subtotal: ₱', '').replace(/,/g, ''));
    const totalAmount = subtotal + deliveryFee;

    // Calculate amount in cents
    const amountInCents = Math.round(totalAmount * 100); // Ensure rounding to avoid float issues

    // Check minimum amount requirement in cents
    if (amountInCents < 2000) { // Minimum amount of ₱20.00
        alert('Total amount must be at least ₱20.00 for checkout.');
        return;
    }

    // If everything is correct, submit the form for checkout
    document.getElementById('checkout-form').submit();
}

// update subtotal
$(document).ready(function() {
    function updateSubtotal() {
        let deliveryPrice = $('input[name="delivery"]:checked').length > 0 
                            ? parseFloat($('input[name="delivery"]:checked').siblings('.option-price').text().replace('₱', '')) 
                            : 0;
        let overallSubtotal = 0;
        $('.cart-item').each(function() {
            let itemPrice = parseFloat($(this).find('.item-price').text().replace('Price: ₱', ''));
            let quantity = parseInt($(this).find('input[type="number"]').val());
            let itemSubtotal = itemPrice * quantity;
            overallSubtotal += itemSubtotal;
            $(this).find('.item-subtotal').text('Subtotal: ₱' + itemSubtotal.toFixed(2));
        });
        $('#subtotal').text('Subtotal: ₱' + overallSubtotal.toFixed(2));
        $('#delivery-cost').text(deliveryPrice.toFixed(2));
        $('#total-amount').text((overallSubtotal + deliveryPrice).toFixed(2));
    }

    updateSubtotal();

    $('input[name="delivery"]').change(updateSubtotal);
    $('.quantity input[type="number"]').change(updateSubtotal);

    // Remove item
    $(".remove-btn").on("click", function(event) {
        event.preventDefault();  // Prevent default form submission
        let productId = $(this).val();  // Get product ID

        $.post('remove_from_cart.php', { product_id: productId }, function(response) {
            console.log(response);  // Log response for debugging
            try {
                let result = JSON.parse(response);
                if (result.status === 'success') {
                    // Item removed from the DOM
                    $(`[data-product-id="${productId}"]`).remove();
                    
                    // Refresh the page to update the cart view
                    location.reload();  // This reloads the page, updating the cart display

                } else {
                    alert(result.message);  // Alert if there's a problem
                }
            } catch (e) {
                console.error("Error parsing JSON:", e);
                alert("Unexpected server response.");
            }
        }).fail(function(xhr) {
            console.error('Error removing item:', xhr.responseText);
            alert('Error: Unable to remove item.');
        });
    });

    // update quantity
    $(".plus, .minus").on("click", function(event) {
        event.preventDefault();
        let input = $(this).siblings("input[type='number']");
        let oldValue = parseInt(input.val());
        let productId = $(this).closest('.cart-item').data('product-id');
        let newValue = $(this).hasClass('plus') ? oldValue + 1 : Math.max(oldValue - 1, 1);
        input.val(newValue);
        $.post('update_cart.php', { product_id: productId, quantity: newValue }, function(response) {
            console.log('Update successful:', response);
            updateSubtotal();
        }).fail(function(xhr) {
            console.error('Update failed:', xhr.responseText);
        });
    });
});
</script>

<!-- sign in popup -->
<div id="popup-modal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);">
    <button type="button" id="close-btn" class="btn-close" aria-label="Close"></button>

    <img src='media/mypassword.svg'>
    <h2 style="text-align: center;">You are not logged in!</h2>
    <p style="text-align: center;">Please log in or sign up to continue.</p>
    <div style="text-align: center;">
        <a href="./user/login2.php"><button id="login-btn" class="btn btn-primary">Log In</button></a>
        <a href="./user/login2.php"> <button id="signup-btn" class="btn btn-primary">Sign Up</button></a>
    </div>
</div>

<script>
document.getElementById('close-btn').onclick = function() {
    document.getElementById('popup-modal').style.display = "none";
};
</script>

<!-- Address Modal -->
<div id="address-modal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);">
    <button type="button" id="close-address-modal" class="btn-close" aria-label="Close"></button>
    <h2 style="text-align: center;">Please Add Your Address</h2>
    <p style="text-align: center;">You need to add an address before proceeding to checkout.</p>
    <div style="text-align: center;">
        <a href="./user/profile.php"><button class="btn btn-primary">Add Address</button></a>
    </div>
</div>

<script>
document.getElementById('close-address-modal').onclick = function() {
    document.getElementById('address-modal').style.display = "none";
};
</script>

<?php
// Function to save cart items to the orders table
function saveCartItemsToOrdersTable($con, $user_id, $cartItems) {
    // Generate transaction ID
    $transactionID = generateTransactionID();

    // Get user details from session
    $user_address = isset($_SESSION['user_address']) ? $_SESSION['user_address'] : null;
    $user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $delivery_option = $_SESSION['delivery_option'] ?? 'Standard/120';

    // Default payment method for all orders (Cash On Delivery)
    $payment_method = 'Cash On Delivery';

    // Iterate through cart items
    foreach ($cartItems as $item) {
        // Extract item details
        $product_id = $item['product_id'];
        $product_price = $item['product_price'];
        $product_name = $item['product_name'];
        $quantity = $item['quantity'];
        $product_image = $item['product_image1'];

        // Set additional order details
        $payment_status = 'Pending';
        $date = date('Y-m-d H:i:s');

        // Prepare the INSERT statement
        $insert_query = "INSERT INTO orders (user_id, transaction_id, product_id, product_name, product_price, product_image, payment_method, address, user_ip, user_email, payment_status, date, quantity, delivery_option) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_query);
        if ($stmt) {
            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, 'isdsssssssssis', $user_id, $transactionID, $product_id, $product_name, $product_price, $product_image, $payment_method, $user_address, $user_ip, $user_email, $payment_status, $date, $quantity, $delivery_option);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing statement: " . mysqli_error($con);
            return false;
        }
    }

    // Remove all cart items for the user
    $delete_query = "DELETE FROM cart_details WHERE user_id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // All insertions successful, return true
    return true;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the payment method is Cash On Delivery
    if ($_POST['payment_method'] === 'CashOnDelivery') {
        // Retrieve cart items for the logged-in user
        $user_id = $_SESSION['user_id'];
        $cart_query = "SELECT * FROM cart_details WHERE user_id = $user_id";
        $result = mysqli_query($con, $cart_query);

        // Initialize an array to store cart items
        $cartItems = [];

        // Loop through cart items and store them in the array
        while ($row = mysqli_fetch_assoc($result)) {
            // Fetch product details from the products table using the product ID
            $product_query = "SELECT product_name, product_price, product_image1 FROM products WHERE product_id = " . $row['product_id'];
            $product_result = mysqli_query($con, $product_query);
            $product_row = mysqli_fetch_assoc($product_result);

            // Merge product details with cart item
            $cartItem = array_merge($row, $product_row);

            // Add merged cart item to the array
            $cartItems[] = $cartItem;
        }

        // Process the cart items (e.g., save to the database)
        if (saveCartItemsToOrdersTable($con, $user_id, $cartItems)) {
            // Set success message in session
            $_SESSION['success_message'] = 'Cart items ordered successfully.';
            // Redirect to cart.php with success parameter
            header("Location: cart.php?success=true");
            exit;
        } else {
            // Error saving cart items
            echo json_encode(array('success' => false, 'message' => 'Error ordering cart items.'));
        }
    }
}
?>

<?php include 'includes/footer.php'; ?>

