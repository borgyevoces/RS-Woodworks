<?php 
include './includes/header.php'; 

function getProductDetailsAndReviews() {
    global $con;

    if (!isset($_GET['product_id'])) return;

    $product_id = intval($_GET['product_id']); // Sanitize product_id

    // Fetch product details from the database
    $select_query = "SELECT * FROM products WHERE product_id = $product_id";
    $result_query = mysqli_query($con, $select_query);
    if (!$result_query || mysqli_num_rows($result_query) === 0) {
        return; // No product found
    }
    
    $row = mysqli_fetch_assoc($result_query);
    $product_images = [
        $row['product_image1'] ?? 'default.jpg',
        $row['product_image2'] ?? 'default.jpg',
        $row['product_image3'] ?? 'default.jpg',
        $row['product_image4'] ?? 'default.jpg',
        $row['product_image5'] ?? 'default.jpg',
    ];
    $total_reviews = getTotalReviews($product_id);

    // Fetch user's address from the database
    $user_id = $_SESSION['user_id'] ?? null; // Get user_id from session
    if ($user_id) {
        $address_query = "SELECT user_address FROM user_table WHERE user_id = $user_id";
        $address_result = mysqli_query($con, $address_query);
        $user_address = ($address_result && mysqli_num_rows($address_result) > 0) ? mysqli_fetch_assoc($address_result)['user_address'] : 'Address not available';
    } else {
        $user_address = 'Address not available'; // If no user is logged in
    }

    echo renderProductDetails($row, $product_images, $total_reviews, $user_address);
    echo renderReviewSection($product_id, $total_reviews['average_rating']);
}

function getTotalReviews($product_id) {
    global $con;
    $reviews_count_query = "SELECT COUNT(*) as total_reviews, AVG(rating) as average_rating FROM reviews WHERE product_id = $product_id";
    $reviews_count_result = mysqli_query($con, $reviews_count_query);
    return mysqli_fetch_assoc($reviews_count_result) ?: ['total_reviews' => 0, 'average_rating' => 0];
}

function renderProductDetails($product, $images, $total_reviews, $user_address) {
    extract($product); // Extract variables from product array
    $formatted_price = number_format($product_price, 2);
    $product_price_display = "₱$formatted_price"; // Format price for display
    
    return "
    <section id='prodetails' class='section-p1'>
        <div class='single-pro-img'>
            <img src='./admin/product_images/{$images[0]}' id='MainImg' alt='{$product_name}'>
            <div class='small-img-group'>
                " . implode('', array_map(fn($img) => "<div class='small-img-col'><img src='./admin/product_images/$img' class='small-img' alt=''></div>", array_slice($images, 1))) . "
            </div>
        </div>
        <div class='single-pro-details'>
            <br><br><br>
            <h6 class='category-title' style='font-weight: 250; font-size: 20px; color: teal;'>{$category_title}</h6>
            <h4 style='font-size: 55px; font-weight: 545; color: black;'>{$product_name}</h4>
            <p class='total-reviews' style='font-size: 20px;'>{$total_reviews['total_reviews']} Reviews</p>
            <h2 class='product-price'>{$product_price_display}</h2>
            <div class='product-actions'>
                <button class='add-to-cart-btn' onclick='addToCart($product_id)'>
                    <i class='bi bi-bag-plus-fill'></i>
                </button>
                <button class='checkout-btn' onclick='openCheckoutModal($product_id, \"$product_name\", \"{$images[0]}\", \"$formatted_price\")'>Check Out</button>
            </div>
            <div class='product-info'>
                <h4 style='margin-bottom: 20px;'>Product Details</h4>
                <h5>Size:</h5>
                <span class='product-description'>{$product_description}</span><br>
                <h5>Description</h5>
                <span class='product-description'>{$product_description2}</span>
            </div>
            <!-- Social Media Share Buttons -->
            <div class='social-share'>
                <p>Share this product:</p>
                <!-- Facebook Share -->
                <a href='https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('https://yourwebsite.com/product.php?id=' . $product_id) ?>' target='_blank'>
                    <button class='social-btn facebook-btn'>
                        <i class='fab fa-facebook-f'></i> Share
                    </button>
                </a>
                <!-- Instagram Share (linking to Instagram) -->
                <a href='https://www.instagram.com/sharing/product/<?= $product_id ?>' target='_blank'>
                    <button class='social-btn instagram-btn'>
                        <i class='fab fa-instagram'></i> Share
                    </button>
                </a>
                <!-- X (Twitter) Share -->
                <a href='https://twitter.com/intent/tweet?text=Check out this product: <?= urlencode('https://yourwebsite.com/product.php?id=' . $product_id) ?>' target='_blank'>
                    <button class='social-btn twitter-btn'>
                        <i class='fa-brands fa-x-twitter'></i> Share
                    </button>
                </a>
            </div>
        </div>
    </section>
    <div id='checkoutModal' class='modal' style='display: none;'>
        <div class='modal-content'>
            <span class='close' onclick='closeCheckoutModal()'>&times;</span>
            <h2>Checkout</h2>
            <img id='modalProductImage' src='' alt='Product Image' style='width: 150px;'>
            <p id='modalProductName' style='font-size: 20px;'></p>
            <div class='quantity-control'>
                <button onclick='decreaseQuantity()'>-</button>
                <input type='number' id='modalQuantity' value='1' min='1' readonly>
                <button onclick='increaseQuantity()'>+</button>
            </div>
            <div class='delivery-address'>
            
                <h2 style='font-weight: bold;'>Delivery Address</h2>
                <div class='current-address'>
                    <h3><i class='bi bi-geo-alt'></i> Current Address:</h3>
                    <p style='font-size:12px;'>" . (isset($user_address) ? $user_address : 'Address not available') . "</p>
                </div>
                <div class='change-address'>
                    <a href='./user/profile.php' style='padding: 8px 10px; background-color: #1976D2; color: #fff; border-radius: 5px;'>Change</a>
                </div>
            </div>
            <div class='delivery-options'>
                <h5 style='font-weight: bold; color: #333;'>
                    <i class='fa-solid fa-truck'></i> Delivery Options
                </h5>
                <label class='delivery-option'>
                    <input type='radio' name='delivery' value='120' onchange='updateTotalAmount();' checked>
                    <span class='option-text'>Standard Delivery</span>
                    <span class='option-details'>3-5 Days</span>
                    <span class='option-price'>₱120.00</span>
                </label>
                <label class='delivery-option'>
                    <input type='radio' name='delivery' value='400' onchange='updateTotalAmount();'>
                    <span class='option-text'>Truck Delivery</span>
                    <span class='option-details'>1-3 Days</span>
                    <span class='option-price'>₱400.00</span>
                </label>
            </div>
            <div class='payment-methods'>
                <h5 style='font-weight: bold; color: #333;'>
                    <i class='fa-solid fa-credit-card'></i> Payment Methods
                </h5>
                <label class='payment-method' style='display: flex; padding: 12px; 0;border: 1px solid #ddd; border-radius: 8px; margin: 10px 0;'>
                    <input type='radio' name='payment_method' value='PayMongo' onchange='updatePaymentMethod();' checked>
                    <span class='option-text'><img src='media/payment-method.png' alt='Pay Online' style='width: 30px; height: 30px; margin-left: 10px;'> Pay Online</span>
                </label>
                <label class='payment-method' style='display: flex; padding: 12px; 0;border: 1px solid #ddd; border-radius: 8px; margin: 10px 0;'>
                    <input type='radio' name='payment_method' value='CashOnDelivery' onchange='updatePaymentMethod();'>
                    <span class='option-text'><img src='media/fast-delivery.png' alt='Cash on Delivery' style='width: 30px; height: 30px; margin-left: 10px;'> Cash on Delivery</span>
                </label>
            </div>
            <h2 style='font-weight: bold;'>Overall Total</h2>
            <div class='subtotal'>
                <span id='subtotal'>Subtotal: ₱<span id='subtotal-value'>0.00</span></span>
            </div>
            <div class='delivery-fee'>
                <span id='delivery-fee'>Delivery Fee: ₱<span id='delivery-cost'>0.00</span></span>
            </div>
            <div class='total-amount'>
                <strong>Total Amount: ₱<span id='total-amount'>0.00</span></strong>
            </div>
            <form id='checkout-form' action='transaction/indivcheckout.php' method='POST'>
                <input type='hidden' name='product_id' value='{$product_id}'>
                <input type='hidden' id='modalQuantityInput' name='quantity' value='1'>
                <input type='hidden' id='deliveryFeeInput' name='delivery_fee' value='120'>
                <input type='hidden' id='deliveryOptionInput' name='delivery_option' value='Standard'>
                <input type='hidden' id='paymentMethodInput' name='payment_method' value='PayMongo'>
                <button type='submit' onclick='initiateCheckout();'>Proceed to Checkout</button>
            </form>
        </div>
    </div>
    <script>

        function openCheckoutModal(productId, productName, productImage, productPrice) {
            document.getElementById('modalProductName').textContent = productName;
            document.getElementById('modalProductImage').src = './admin/product_images/' + productImage;
            document.getElementById('checkoutModal').style.display = 'flex'; // Show modal
            updateTotalAmount();
        }

        function closeCheckoutModal() {
            document.getElementById('checkoutModal').style.display = 'none'; // Hide modal
        }

        function increaseQuantity() {
            let quantityInput = document.getElementById('modalQuantity');
            let quantity = parseInt(quantityInput.value);
            quantityInput.value = quantity + 1;
            document.getElementById('modalQuantityInput').value = quantity + 1;
            updateTotalAmount();
        }

        function decreaseQuantity() {
            let quantityInput = document.getElementById('modalQuantity');
            let quantity = parseInt(quantityInput.value);
            if (quantity > 1) {
                quantityInput.value = quantity - 1;
                document.getElementById('modalQuantityInput').value = quantity - 1;
                updateTotalAmount();
            }
        }

        function updateTotalAmount() {
            let quantity = parseInt(document.getElementById('modalQuantity').value);
            let productPrice = parseFloat('$formatted_price');
            let deliveryFee = parseFloat(document.querySelector('input[name=\"delivery\"]:checked').value);
            let deliveryOption = document.querySelector('input[name=\"delivery\"]:checked').nextElementSibling.textContent;
            document.getElementById('deliveryOptionInput').value = deliveryOption;

            let subtotal = quantity * productPrice;
            document.getElementById('subtotal-value').textContent = subtotal.toFixed(2);
            document.getElementById('delivery-cost').textContent = deliveryFee.toFixed(2);
            document.getElementById('deliveryFeeInput').value = deliveryFee;

            let totalAmount = subtotal + deliveryFee;
            document.getElementById('total-amount').textContent = totalAmount.toFixed(2);
        }

        function updatePaymentMethod() {
            let paymentMethod = document.querySelector('input[name=\"payment_method\"]:checked').value;
            document.getElementById('paymentMethodInput').value = paymentMethod;
        }

        function initiateCheckout() {
            const paymentMethod = document.getElementById('paymentMethodInput').value;
            if (paymentMethod === 'CashOnDelivery') {
                document.getElementById('checkout-form').action = './transaction/cash_on_delivery.php';
            } else {
                document.getElementById('checkout-form').action = './transaction/indivcheckout.php';
            }
            document.getElementById('checkout-form').submit();
        }

        var MainImg = document.getElementById('MainImg');
        document.querySelectorAll('.small-img').forEach((img, index) => {
            img.onclick = () => MainImg.src = img.src;
        });
    </script>
    ";
}

function renderReviewSection($product_id, $average_rating) {
    global $con;

    // Ensure the user is logged in and has purchased the product
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Query to check if the user has purchased this product
        $query = "SELECT * FROM completed_order WHERE user_id = ? AND product_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // If the user has not purchased the product, do not allow review
        $can_review = mysqli_num_rows($result) > 0;
        mysqli_stmt_close($stmt);
    } else {
        $can_review = false;
    }

    // Display reviews and review form
    ob_start(); // Start output buffering
    ?>
    <section id='reviewSection' style="margin-top: 0;">
        <div class='review-form-container'>
            <h2 class='review-form-title'>Product Ratings:<span class='average-rating'> <?= ($average_rating ? str_repeat('★', intval($average_rating)) . str_repeat('☆', 5 - intval($average_rating)) : 'No ratings yet') ?> (<?= round($average_rating, 1) ?>/5)</span></h2>

            <?php if ($can_review): ?>
                <form class='review-form' method='post' action='submit_review.php' enctype='multipart/form-data'>
                    <div class='rating-container'>
                        <label for='rating' class='rating-label'>Product Quality:</label>
                        <div class='star-rating'>
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type='radio' id='star<?= $i ?>' name='rating' value='<?= $i ?>' required/><label for='star<?= $i ?>' title='<?= $i ?> stars'></label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label for='review' class='review-label'>Your Review:</label>
                        <textarea id='review' name='review' class='review-textarea' rows='4' placeholder='Write your review here...' required></textarea>
                    </div>
                    <div class='form-group'>
                        <label for='review_media' class='review-label'>Attach Media (optional):</label>
                        <div class='file-upload-container'>
                            <label for='review_media' class='file-upload-label'><i class='fas fa-paperclip' title='Attach Media'></i></label>
                            <input type='file' id='review_media' name='review_media[]' accept='image/*,video/*' class='file-input' multiple onchange='updateFileNames(this);'>
                            <span class='file-name' id='mediaFileName'>No file chosen</span>
                        </div>
                    </div>
                    <input type='hidden' name='product_id' value='<?= $product_id ?>'>
                    <button type='submit' class='submit-review-btn'>Submit Review</button>
                </form>
            <?php else: ?>
                <style>
                    .purchase-message {
                        background-color: #f8d7da;  /* Light red background */
                        color: #721c24;             /* Dark red text */
                        border: 1px solid #f5c6cb; /* Light red border */
                        padding: 15px 20px;         /* Padding for spacing */
                        border-radius: 5px;         /* Rounded corners */
                        font-size: 16px;            /* Font size */
                        display: flex;              /* Flexbox for icon and text */
                        align-items: center;        /* Align text and icon vertically */
                        margin-top: 20px;           /* Spacing from other elements */
                    }

                    .purchase-message i {
                        font-size: 20px;           /* Icon size */
                        margin-right: 10px;        /* Space between the icon and the text */
                    }
                </style>
                <p class="purchase-message">
                    <i class="fas fa-exclamation-circle"></i> You must purchase this product before leaving a review.
                </p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <p><a href='user/login.php' class='login-link'>Log in to leave a review</a></p>
                <?php endif; ?>
            <?php endif; ?>

            <h2 class='customer-reviews-title'>Customer Reviews</h2>
            <div class='reviews-container'>
                <?= renderExistingReviews($product_id) ?>
            </div>
        </div>
    </section>

    <script>
    function updateFileNames(input) {
        document.getElementById('mediaFileName').innerText = Array.from(input.files).map(file => file.name).join(', ') || 'No file chosen';
    }
    </script>

    <?php
    return ob_get_clean(); // Return the output buffer content
}

function renderExistingReviews($product_id) {
    global $con;

    $reviews_query = "SELECT reviews.*, user_table.full_name, user_table.user_image FROM reviews JOIN user_table ON reviews.user_id = user_table.user_id WHERE reviews.product_id = $product_id ORDER BY reviews.created_at DESC";
    $reviews_result = mysqli_query($con, $reviews_query);
    
    if (!$reviews_result || mysqli_num_rows($reviews_result) === 0) {
        return "<div  style='text-align: center; '>
                        <img style='height: 200px; width: 250px; margin-top: 40px;' src='./media/noreviews.svg'>
                    </div>
                    <p style='text-align: center; font-size: 25px; color: #333; font-weight: 500; margin-bottom: 100px;'>No reviews yet. Be the first to review this product.</p>";
    }
    
    $reviews_html = "";
    while ($review = mysqli_fetch_assoc($reviews_result)) {
        $full_name = htmlspecialchars($review['full_name']);
        $user_image = htmlspecialchars($review['user_image'] ?: './user/user_images/defaultuser.png');
        $rating = intval($review['rating']);
        $review_text = htmlspecialchars($review['review']);
        $created_at = htmlspecialchars($review['created_at']);
        $review_image = htmlspecialchars($review['image_path']);
        $review_video = htmlspecialchars($review['video_path']);
        $review_id = $review['id'];
        
        // Format the created_at timestamp
        $formatted_date = date('l, F j, Y \a\t g:i A', strtotime($created_at));

        $reviews_html .= "<div class='single-review'>
        <div class='review-header'>
            <div class='review-user-info'>
                <img src='./user/$user_image' alt='$full_name' class='review-user-image'>
                <strong class='review-username'>$full_name</strong>
            </div>
            <div class='review-rating'>
                " . str_repeat('★', $rating) . str_repeat('☆', 5 - $rating) . " <span class='rating-count'>($rating/5)</span>
            </div>
        </div>
        <p class='review-text'>$review_text</p>";

        if (!empty($review_image) || !empty($review_video)) {
            $reviews_html .= "<div class='review-media'>";
            if (!empty($review_image)) {
                $reviews_html .= "<img src='./$review_image' alt='Review Image' class='review-image'>";
            }
            if (!empty($review_video)) {
                $reviews_html .= "<video src='./$review_video' controls class='review-video'></video>";
            }
            $reviews_html .= "</div>";
        }

        // Display the formatted date
        $reviews_html .= "<p class='review-date'>Posted on $formatted_date</p>";


        // Edit and Delete Buttons for the user who posted the review
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']) {
            $reviews_html .= "<div class='review-actions'>
                <button class='edit-review-btn' title='Edit Review' data-review-id='$review_id' data-review-text='" . htmlspecialchars($review_text) . "' data-rating='$rating'>
                    <i class='fas fa-edit'></i>
                </button>
                <button class='delete-review-btn' title='Delete Review' onclick='confirmDelete($review_id);'>
                    <i class='fas fa-trash-alt'></i>
                </button>
            </div>";
        }

        $reviews_html .= "</div>"; // Close single-review div
    }

    // Add the modal HTML as before
    $reviews_html .= "<div id='editReviewModal' class='modal'>
        <div class='modal-content'>
            <span class='close-modal'>&times;</span>
            <h2>Edit Your Review</h2>
            <form class='edit-review-form' method='post' action='edit_review.php'>
                <div>
                    <label for='rating'>Rating:</label>
                    <div class='star-rating'>";

                for ($i = 5; $i >= 1; $i--) {
                    $reviews_html .= "<input type='radio' id='edit-star$i' name='rating' value='$i' required>
                                    <label for='edit-star$i' title='$i stars'></label>";
                }

                $reviews_html .= "   </div>
                </div>
                <div>
                    <label for='review'>Review:</label>
                    <textarea name='review' id='modalReviewText' required></textarea>
                </div>
                <input type='hidden' name='review_id' id='modalReviewId' value=''>
                <input type='hidden' name='product_id' value='$product_id'>
                <button type='submit'>Update Review</button>
            </form>
        </div>
    </div>";

    // Confirmation modal for deletion
    // Add this HTML to the existing renderExistingReviews function
    $reviews_html .= "<div id='confirmDeleteModal' class='confirmation-modal' style='display: none;'>
    <div class='confirmation-modal-content'>
        <span class='close-modal'>&times;</span>
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this review?</p>
        <button id='confirmDeleteBtn'>Yes, Delete</button>
        <button id='cancelDeleteBtn'>Cancel</button>
    </div>

    </div>";
    // JavaScript for confirmation modal
    $reviews_html .= "<script>
    var deleteModal = document.getElementById('confirmDeleteModal');

    function confirmDelete(reviewId) {
        deleteModal.style.display = 'block';

        document.getElementById('confirmDeleteBtn').onclick = function() {
            window.location.href = 'delete_review.php?review_id=' + reviewId + '&product_id=$product_id';
        };

        document.getElementById('cancelDeleteBtn').onclick = function() {
            deleteModal.style.display = 'none';
        };
    }

    var closeModal = document.querySelectorAll('.close-modal');
    closeModal.forEach(function(btn) {
        btn.onclick = function() {
            deleteModal.style.display = 'none';
        };
    });

    window.onclick = function(event) {
        if (event.target == deleteModal) {
            deleteModal.style.display = 'none';
        }
    }
    </script>";


    // JavaScript for modals
    $reviews_html .= "<script>
    var modal = document.getElementById('editReviewModal');
    var deleteModal = document.getElementById('confirmDeleteModal');
    var editButtons = document.querySelectorAll('.edit-review-btn');
    var closeModal = document.querySelectorAll('.close-modal');

    editButtons.forEach(function(button) {
        button.onclick = function() {
            var reviewId = button.getAttribute('data-review-id');
            var reviewText = button.getAttribute('data-review-text');
            var rating = button.getAttribute('data-rating');
            
            document.getElementById('modalReviewId').value = reviewId;
            document.getElementById('modalReviewText').value = reviewText;
            
            // Set the correct rating by checking the corresponding star
            var star = document.getElementById('edit-star' + rating);
            if (star) {
                star.checked = true;
            }
            modal.style.display = 'block';
        };
    });

    closeModal.forEach(function(btn) {
        btn.onclick = function() {
            modal.style.display = 'none';
            deleteModal.style.display = 'none';
        }
    });

    window.onclick = function(event) {
        if (event.target == modal || event.target == deleteModal) {
            modal.style.display = 'none';
            deleteModal.style.display = 'none';
        }
    }

    function confirmDelete(reviewId) {
        deleteModal.style.display = 'block';

        document.getElementById('confirmDeleteBtn').onclick = function() {
            window.location.href = 'delete_review.php?review_id=' + reviewId + '&product_id=$product_id';
        };

        document.getElementById('cancelDeleteBtn').onclick = function() {
            deleteModal.style.display = 'none';
        };
    }
    </script>
    
    ";

    return $reviews_html;
}

getProductDetailsAndReviews();

?>
<div class="page-title" style="margin-top: 0; margin-bottom: 30px;">
    <h1> Featured Products </h1>
    <p>Products you might also like</p>
</div>
    <div style=" margin-bottom:100px;" class="pro-container">
        <!-- Fetching products -->
        <?php 
            // Calling the cart function
            if (isset($_GET['add_to_cart'])) {
                $productId = intval($_GET['add_to_cart']);
                handleCartRequest($productId); // Pass product ID to handleCartRequest
            }
            getAllProducts(); // This function now includes pagination logic
        ?>
    </div>
<script>
var modal = document.getElementById('editReviewModal');
var editButtons = document.querySelectorAll('.edit-review-btn');
var closeModal = document.querySelector('.close-modal');

editButtons.forEach(function(button) {
    button.onclick = function() {
        var reviewId = button.getAttribute('data-review-id');
        var reviewText = button.getAttribute('data-review-text');
        var rating = button.getAttribute('data-rating');
        
        document.getElementById('modalReviewId').value = reviewId;
        document.getElementById('modalReviewText').value = reviewText;

        // Set the correct rating by checking the corresponding star
        var star = document.getElementById('edit-star' + rating);
        if (star) {
            star.checked = true;
        }
    
        modal.style.display = 'block';
    };
});

closeModal.onclick = function() {
    modal.style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Show success popup if redirected after successful order
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('payment_success') === '1') {
        document.getElementById('success-popup').style.display = 'block';
    }
    document.querySelector('.success-popup .close-btn').addEventListener('click', function() {
        document.getElementById('success-popup').style.display = 'none';
    });
});
</script>

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

<!-- Modal HTML -->
<div class="modal-container" id="addtocartmodal" style="display: none;">
    <div class="modal-background">
        <div class="modal-content">
            <p style="text-align: center; margin-bottom: none;" id="modalContent"></p>
        </div>
    </div>
</div>


<script>
 // Function to show the modal with a specific message
function showModal(message) {
    console.log('Showing modal with message:', message);
    var modalContent = document.getElementById('modalContent');
    modalContent.textContent = message;
    var modal = document.getElementById('addtocartmodal');
    modal.style.display = 'block';

    // Automatically close the modal after 1.5 seconds
    setTimeout(closeModal, 1500);
}

// Function to close the modal
function closeModal() {
    var modal = document.getElementById('addtocartmodal');
    modal.style.display = 'none';
}

// Function to dynamically update the cart count
function updateCartCount() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            document.querySelector(".badge.bg-danger").textContent = this.responseText;
        }
    };
    xhr.open("GET", "cart_item_count.php", true);
    xhr.send();
}

// Updated addToCart function
function addToCart(productId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                try {
                    var response = JSON.parse(this.responseText);
                    console.log('Parsed response:', response);

                    if (response.message === "Item has been added to the cart.") {
                        showModal("Item has been added to the cart.");
                        updateCartCount();
                    } else if (response.message === "This item is already in your cart.") {
                        showModal("This item is already in your cart.");
                    } else {
                        showModal("An error occurred. Please try again later.");
                    }
                } catch (error) {
                    console.error("Error parsing JSON:", error);
                    showModal("An error occurred. Please try again later.");
                }
            } else {
                console.error("Request failed with status:", this.status);
                showModal("An error occurred. Please try again later.");
            }
        }
    };
    xhr.open("GET", "product_details.php?add_to_cart=" + productId, true);
    xhr.send();
}
</script>

<!-- Login Alert Modal -->
<div class="modal-container" id="loginAlertModal" style="display: none; z-index: 1051;">
    <div class="modal-background">
        <div class="modal-content">
            <img src="media/signup.svg" alt="Sign Up" style="width: 100px; height: 100px; display: block; margin: 0 auto;">
            <p style="text-align: center; margin-top: 20px;">Please log in or create an account to continue.</p>
            <div style="text-align: center; margin-top: 20px;">
                <a href="user/login.php" class="btn btn-primary">Log In</a>
                <a href="user/register.php" class="btn btn-secondary">Create Account</a>
            </div>
        </div>
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
</body>
</html>

<?php include './includes/footer.php';?>