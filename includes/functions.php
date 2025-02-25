<?php

$con=mysqli_connect('localhost', 'root', '','rswoodworks');

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
} else {
    
}?>
<?php
//getting products

function getAllProducts() {
    global $con;

    // Set the number of products per page
    $products_per_page = 3;

    // Get the current page number from the URL (defaults to 1 if not set)
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Calculate the offset for the SQL query
    $offset = ($current_page - 1) * $products_per_page;

    // Modify the query to get all products, including planks, and randomize the order
    $select_query = "SELECT * FROM `products` ORDER BY RAND() LIMIT $offset, $products_per_page";
    $result_query = mysqli_query($con, $select_query);

    while($row = mysqli_fetch_assoc($result_query)){
        $product_id = $row['product_id'];
        $product_name = $row['product_name'];
        $product_image1 = $row['product_image1'];
        $product_keyword = $row['product_keyword'];
        $product_price = $row['product_price'];
        $category_title = $row['category_title'];
        $product_description = $row['product_description'];
        $promo = $row['promo'];

        // Fetch the average rating for the product
        $rating_query = "SELECT AVG(rating) AS avg_rating, COUNT(id) AS total_ratings 
                         FROM `reviews` 
                         WHERE product_id = $product_id";
        $rating_result = mysqli_query($con, $rating_query);

        // Default values if no ratings
        $avg_rating = 0;
        $total_ratings = 0;

        // Fetch rating data if available
        if ($rating_result && mysqli_num_rows($rating_result) > 0) {
            $rating_data = mysqli_fetch_assoc($rating_result);
            $avg_rating = round($rating_data['avg_rating'], 1);
            $total_ratings = $rating_data['total_ratings'];
        }

        // Build star rating HTML
        $full_stars = floor($avg_rating);
        $half_star = ($avg_rating - $full_stars >= 0.5) ? '½' : '';
        $empty_stars = 5 - $full_stars - (empty($half_star) ? 0 : 1);
        $stars_html = str_repeat('★', $full_stars) . $half_star . str_repeat('☆', $empty_stars);

        // Output the product HTML
        echo "
            <a href='product_details.php?product_id=$product_id' class='pro' style='position: relative; text-decoration: none; color: inherit;'> <!-- Add position relative to product card -->
                <!-- Promo Section -->
                " . (!empty($promo) ? "<div class='promo-label' style='position: absolute; top: 0; right: 0; background-color: rgba(65, 105, 225, 0.5); color: white; padding: 10px 15px; border-radius: 0 0 0 10px; font-weight: bold; font-size: 16px;'>$promo</div>" : "") . "
                
                <div class='image-container'>
                    <img src='./admin/product_images/$product_image1' alt='Product Image'>
                </div>
                
                <div class='des'>
                    <span style='color: teal;'>$category_title</span>
                    <h5>$product_name</h5>
                    <p style='font-size: 14px; margin-top: 5px; font-weight: 400;'>$product_description</p>
                    <h4><span style='color: black; margin-right: 2px; font-size: 14px;'>₱</span>$product_price</h4>

                    <!-- Rating Display -->
                    <div class='rating'>
                        <span>$stars_html</span>
                        <span>($avg_rating)</span>
                    </div>

                    <!-- Add to Cart Button -->
                    <button class='add-to-cart-btn' onclick='event.preventDefault(); addToCart($product_id);' style='display: none; width: 70%; background-color: navyblue; color: white; border: none; padding: 5px; border-radius: 5px; cursor: pointer; margin-top: 10px;'>
                        <i class='bi bi-cart-fill' style='margin-right: 5px;'></i>Add to Cart
                    </button>
                </div>
            </a>

            <script>
                // JavaScript to show the button on hover
                document.querySelectorAll('.pro').forEach(function(pro) {
                    pro.addEventListener('mouseover', function() {
                        this.querySelector('.add-to-cart-btn').style.display = 'block';
                    });
                    pro.addEventListener('mouseout', function() {
                        this.querySelector('.add-to-cart-btn').style.display = 'none';
                    });
                });
            </script>";
    }
}


function getNonPlankProducts() {
    global $con;

    // Set the number of products per page
    $products_per_page = 6;

    // Get the current page number from the URL (defaults to 1 if not set)
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Calculate the offset for the SQL query
    $offset = ($current_page - 1) * $products_per_page;

    if (!isset($_GET['category'])) {
        // Modify the query to exclude Planks and hidden products
        $select_query = "SELECT * FROM `products` 
                         WHERE `category_title` != 'Planks' AND `hidden` != 'yes' 
                         LIMIT $offset, $products_per_page";
        $result_query = mysqli_query($con, $select_query);

        while ($row = mysqli_fetch_assoc($result_query)) {
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $product_image1 = $row['product_image1'];
            $product_keyword = $row['product_keyword'];
            $product_price = $row['product_price'];
            $category_title = $row['category_title'];
            $product_description = $row['product_description'];
            $promo = $row['promo'];

            // Fetch the average rating for the product
            $rating_query = "SELECT AVG(rating) AS avg_rating, COUNT(id) AS total_ratings 
                             FROM `reviews` 
                             WHERE product_id = $product_id";
            $rating_result = mysqli_query($con, $rating_query);

            // Default values if no ratings
            $avg_rating = 0;
            $total_ratings = 0;

            // Fetch rating data if available
            if ($rating_result && mysqli_num_rows($rating_result) > 0) {
                $rating_data = mysqli_fetch_assoc($rating_result);
                $avg_rating = round($rating_data['avg_rating'], 1);
                $total_ratings = $rating_data['total_ratings'];
            }

            // Build star rating HTML
            $full_stars = floor($avg_rating);
            $half_star = ($avg_rating - $full_stars >= 0.5) ? '½' : '';
            $empty_stars = 5 - $full_stars - (empty($half_star) ? 0 : 1);
            $stars_html = str_repeat('★', $full_stars) . $half_star . str_repeat('☆', $empty_stars);

            // Output the product HTML
            echo "
            <a href='product_details.php?product_id=$product_id' class='pro' style='position: relative; text-decoration: none; color: inherit;'> <!-- Add position relative to product card -->
                <!-- Promo Section -->
                " . (!empty($promo) ? "<div class='promo-label' style='position: absolute; top: 0; right: 0; background-color: rgba(65, 105, 225, 0.5); color: white; padding: 10px 15px; border-radius: 0 0 0 10px; font-weight: bold; font-size: 16px;'>$promo</div>" : "") . "
                
                <div class='image-container'>
                    <img src='./admin/product_images/$product_image1' alt='Product Image'>
                </div>
                
                <div class='des'>
                    <span style='color: teal;'>$category_title</span>
                    <h5>$product_name</h5>
                    <p style='font-size: 14px; margin-top: 5px; font-weight: 400; '>$product_description</p>
                    <h4><span style='color: black; margin-right: 2px; font-size: 14px;'>₱</span>$product_price</h4>

                    <!-- Rating Display -->
                    <div class='rating'>
                        <span>$stars_html</span>
                        <span>($avg_rating)</span>
                    </div>

                    <!-- Add to Cart Button -->
                    <button class='add-to-cart-btn' onclick='event.preventDefault(); addToCart($product_id);' style='display: none; width: 70%; background-color: navyblue; color: white; border: none; padding: 5px; border-radius: 5px; cursor: pointer; margin-top: 10px;'>
                        <i class='bi bi-cart-fill' style='margin-right: 5px;'></i>Add to Cart
                    </button>
                </div>
            </a>

            <script>
                // JavaScript to show the button on hover
                document.querySelectorAll('.pro').forEach(function(pro) {
                    pro.addEventListener('mouseover', function() {
                        this.querySelector('.add-to-cart-btn').style.display = 'block';
                    });
                    pro.addEventListener('mouseout', function() {
                        this.querySelector('.add-to-cart-btn').style.display = 'none';
                    });
                });
            </script>";
        }
    }
}

function getPlankProducts() {
    global $con;

    // Exclude hidden products from Planks category
    $select_query = "SELECT * FROM `products` 
                     WHERE `category_title` = 'Planks' AND `hidden` != 'yes' 
                     LIMIT 0, 12";
    $result_query = mysqli_query($con, $select_query);

    while ($row = mysqli_fetch_assoc($result_query)) {
        $product_id = $row['product_id'];
        $product_name = $row['product_name'];
        $product_image1 = $row['product_image1'];
        $product_keyword = $row['product_keyword'];
        $product_price = $row['product_price'];
        $category_title = $row['category_title'];
        $product_description = $row['product_description'];
        $promo = $row['promo'];

        // Fetch the average rating for the product
        $rating_query = "SELECT AVG(rating) AS avg_rating, COUNT(id) AS total_ratings 
                         FROM `reviews` 
                         WHERE product_id = $product_id";
        $rating_result = mysqli_query($con, $rating_query);

        // Default values if no ratings
        $avg_rating = 0;
        $total_ratings = 0;

        // Fetch rating data if available
        if ($rating_result && mysqli_num_rows($rating_result) > 0) {
            $rating_data = mysqli_fetch_assoc($rating_result);
            $avg_rating = round($rating_data['avg_rating'], 1);
            $total_ratings = $rating_data['total_ratings'];
        }

        // Build star rating HTML
        $full_stars = floor($avg_rating);
        $half_star = ($avg_rating - $full_stars >= 0.5) ? '½' : '';
        $empty_stars = 5 - $full_stars - (empty($half_star) ? 0 : 1);
        $stars_html = str_repeat('★', $full_stars) . $half_star . str_repeat('☆', $empty_stars);

        // Output the product HTML
        echo "
            <a href='product_details.php?product_id=$product_id' class='pro' style='position: relative; text-decoration: none; color: inherit;'> <!-- Add position relative to product card -->
                <!-- Promo Section -->
                " . (!empty($promo) ? "<div class='promo-label' style='position: absolute; top: 0; right: 0; background-color: rgba(65, 105, 225, 0.5); color: white; padding: 10px 15px; border-radius: 0 0 0 10px; font-weight: bold; font-size: 16px;'>$promo</div>" : "") . "
                
                <div class='image-container'>
                    <img src='./admin/product_images/$product_image1' alt='Product Image'>
                </div>
                
                <div class='des'>
                    <span style='color: teal;'>$category_title</span>
                    <h5>$product_name</h5>
                    <p style='font-size: 14px; margin-top: 5px; font-weight: 400; '>$product_description</p>
                    <h4><span style='color: black; margin-right: 2px; font-size: 14px;'>₱</span>$product_price</h4>

                    <!-- Rating Display -->
                    <div class='rating'>
                        <span>$stars_html</span>
                        <span>($avg_rating)</span>
                    </div>

                    <!-- Add to Cart Button -->
                    <button class='add-to-cart-btn' onclick='event.preventDefault(); addToCart($product_id);' style='display: none; width: 70%; background-color: navyblue; color: white; border: none; padding: 5px; border-radius: 5px; cursor: pointer; margin-top: 10px;'>
                        <i class='bi bi-cart-fill' style='margin-right: 5px;'></i>Add to Cart
                    </button>
                </div>
            </a>

            <script>
                // JavaScript to show the button on hover
                document.querySelectorAll('.pro').forEach(function(pro) {
                    pro.addEventListener('mouseover', function() {
                        this.querySelector('.add-to-cart-btn').style.display = 'block';
                    });
                    pro.addEventListener('mouseout', function() {
                        this.querySelector('.add-to-cart-btn').style.display = 'none';
                    });
                });
            </script>";
    }
}

function get_unique_categories() {
    global $con;

    // Check if the 'category' key is set in $_GET array
    if (isset($_GET['category'])) {
        $category_title = mysqli_real_escape_string($con, $_GET['category']); // Escape the input to prevent SQL injection

        // Query products based on the specified category_title
        $select_query = "SELECT * FROM `products` WHERE category_title = '$category_title'";
        $result_query = mysqli_query($con, $select_query);

        // Check if there are any products in the specified category
        if (mysqli_num_rows($result_query) > 0) {
            // Loop through the results and display each product
            while ($row = mysqli_fetch_assoc($result_query)) {
                $product_id = $row['product_id'];
                $product_name = $row['product_name'];
                $product_image1 = $row['product_image1'];
                $product_keyword = $row['product_keyword'];
                $product_price = $row['product_price'];
                $category_title = $row['category_title'];
                $product_description = $row['product_description'];

                echo "<div class='pro'>
                        <div class='image-container'>
                            <img src='./admin/product_images/$product_image1' alt='Product Image'>
                        </div>
                        <div class='des'>
                            <span style='color: teal;'>$category_title</span>
                            <a href='product_details.php?product_id=$product_id'><h5>$product_name</h5></a>
                            <p style='font-size: 14px; margin-top: 5px; font-weight: 400; font-style:oblique;'>$product_description</p>
                            <h4><span style='color: black; margin-right: 2px; font-size: 14px;'>₱</span>$product_price</h4>
                            <button style='background: none; border:none;' onclick='addToCart($product_id)' style='width: 150px'><i class='bi bi-bag-plus-fill' style='font-size: 30px;'></i></button>
                        </div>
                    </div>";
                }
            } else {
                echo "<div class='no-product' style='margin-top: 10px; margin-bottom: 200px; 
                flex-direction: column; align-items: center;'> 
                <img src='media/emptycategory.svg' width='300px' height='300px'><br>
                <p style='font-size: 25px;'><strong>No products found in this category.</strong></p></div>";
            }
        } else {
            echo "";
        }
    }


// getting produc details
function getdetails(){
    global $con;
    //condition to check isset or no
    if(isset($_GET['product_id'])){
        if(!isset($_GET['category'])){
            $product_id=$_GET['product_id'];
            $select_query="Select * from `products` where product_id=$product_id";
            $result_query=mysqli_query($con,$select_query);
            while($row=mysqli_fetch_assoc($result_query)){
                $product_id=$row['product_id'];
                $product_name=$row['product_name'];
                $product_description=$row['product_description'];
                $product_description2=$row['product_description2'];
                $product_keyword=$row['product_keyword'];
                $product_image1=$row['product_image1'];
                $product_image2=$row['product_image2']; // Assign product_image2
                $product_image3=$row['product_image3']; // Assign product_image3
                $product_image4=$row['product_image4']; // Assign product_image4
                $product_price=$row['product_price'];
                $category_title=$row['category_title'];

                echo " <section id='prodetails' class='section-p1'>
                    <div class='single-pro-img'>
                        <image src='./admin/product_images/$product_image1' style='margin-left: 120px;' width='300px' height: 300px; id='MainImg' alt=''>
                        <div class='small-img-group'>
                            <div class='small-img-col'>
                                <image src='./admin/product_images/$product_image1' width='100%' class='small-img' alt=''>
                            </div>
                            <div class='small-img-col'>
                                <image src='./admin/product_images/$product_image2' width='100%' class='small-img' alt=''>
                            </div>
                            <div class='small-img-col'>
                                <image src='./admin/product_images/$product_image3' width='100%' class='small-img' alt=''>
                            </div>
                            <div class='small-img-col'>
                                <image src='./admin/product_images/$product_image4' width='100%' class='small-img' alt=''>
                            </div>
                        </div>
                    </div>
                
                    <div class='single-pro-details'>
                        <h6 style='font-weight: bold; font-size: 18px;'>$category_title</h6>
                        <h4 style='font-size: 30px; font-weight: 545; color: black;'>$product_name</h4>
                        <h2>₱$product_price</h2>
                        <input type='number' value='1'>
                        <button onclick='addToCart($product_id)' style='width: 150px'><i class='bi bi-bag-plus-fill' style='font-size: 17px;'></i>Add To Cart</button>
                        <h4>Product Details</h4>
                        <span>$product_description</span>
                        <br>
                        <br>
                        <span>$product_description2</span>
                    </div>
                </section>

                <script>
                var MainImg = document.getElementById('MainImg');
                var smallimg = document.getElementsByClassName('small-img');
            
                smallimg[0].onclick = function(){
                    MainImg.src = smallimg[0].src;
                }
                smallimg[1].onclick = function(){
                    MainImg.src = smallimg[1].src;
                }
                smallimg[2].onclick = function(){
                    MainImg.src = smallimg[2].src;
                }
                smallimg[3].onclick = function(){
                    MainImg.src = smallimg[3].src;
                }
            </script>";
            }
        }
    }  
}

//get IP ADDRESS FUNCTION
function getIPAddress() {  
    //whether ip is from the share internet  
     if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                $ip = $_SERVER['HTTP_CLIENT_IP'];  
        }  
    //whether ip is from the proxy  
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     }  
//whether ip is from the remote address  
    else{  
             $ip = $_SERVER['REMOTE_ADDR'];  
     }  
     return $ip;  
}  



// Function to handle cart operations (adding an item or preventing duplicates)
function handleCartRequest($productId) {
    global $con;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(array("message" => "User not logged in."));
        return;
    }

    $user_id = $_SESSION['user_id'];

    // Debugging user_id and productId
    error_log("Session user_id: " . $user_id);
    error_log("Product ID: " . $productId);

    $select_query = "SELECT * FROM `cart_details` WHERE user_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($con, $select_query);

    if (!$stmt) {
        echo json_encode(array("message" => "Database error: Unable to prepare statement."));
        return;
    }

    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $productId);
    mysqli_stmt_execute($stmt);
    $result_query = mysqli_stmt_get_result($stmt);
    $cart_item = mysqli_fetch_assoc($result_query);

    // Debugging cart item
    error_log("Cart item fetch result: " . print_r($cart_item, true));

    if ($cart_item) {
        $response = array("message" => "This item is already in your cart.");
    } else {
        $insert_query = "INSERT INTO `cart_details` (product_id, user_id, quantity) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $insert_query);

        if (!$stmt) {
            echo json_encode(array("message" => "Database error: Unable to prepare insert statement."));
            return;
        }

        $quantity = 1;
        mysqli_stmt_bind_param($stmt, 'iii', $productId, $user_id, $quantity);

        if (mysqli_stmt_execute($stmt)) {
            $response = array("message" => "Item has been added to the cart.");
        } else {
            error_log("MySQL error while inserting into cart: " . mysqli_error($con));
            $response = array("message" => "An error occurred while adding the item. Please try again later.");
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


// Call the function with the provided productId parameter
if (isset($_GET['add_to_cart'])) {
    $productId = intval($_GET['add_to_cart']); // Ensure product ID is an integer
    handleCartRequest($productId);
}



// Number OF CART ITEMS FUNCTION
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

// total product price function
function total_cart_price(){
    global $con;
    $get_ip_add = getIPAddress();
    $total_price=0;
    $cart_query="Select * from `cart_details` where ip_address='$get_ip_add'";
    $result=mysqli_query($con,$cart_query);
    while($row=mysqli_fetch_array($result)){
        $product_id=$row['product_id'];
        $select_products="Select * from `products` where product_id='$product_id'";
        $result_products=mysqli_query($con,$select_products);
        while($row_product_price=mysqli_fetch_array($result_products)){
    $product_price=array($row_product_price['product_price']); //[200, 300]
    $product_values=array_sum($product_price);//[500]
    $total_price+=$product_values; //[500]
        }
    }
    echo $total_price;
}
?>