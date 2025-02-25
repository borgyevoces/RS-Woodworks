<?php include 'includes/header.php'; ?>
<main style="margin-bottom: 0; padding-bottom: 0;">
<!-- Login Alert Modal -->
<div class="modal-container" id="loginAlertModal" style="display: none; z-index: 1051;">
    <div class="modal-background">
        <div class="modal-content">
            <img src="media/signup.svg" alt="Sign Up" style="width: 300px; height: 250px; display: block; margin: 0 auto; margin-top: 20px;">
            <p style="text-align: center; margin-top: 20px;">Please log in or create an account to continue.</p>
            <div style="text-align: center; margin-top: 20px;">
                <a href="user/login.php" class="btn btn-primary">Log In</a>
                <a href="user/register.php" class="btn btn-secondary">Create Account</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<div class="modal-container" id="addtocartmodal" style="display: none; none; z-index: 1051;">
    <div class="modal-background">
        <div class="modal-content">
            <p style="text-align: center; margin-bottom: none;" id="modalContent"></p>
        </div>
    </div>
</div>



<script>
function showModal(message) {
    var modalContent = document.getElementById('modalContent');
    modalContent.textContent = message;
    var modal = document.getElementById('addtocartmodal');
    modal.style.display = 'block';
    setTimeout(closeModal, 1500);
}

function closeModal() {
    var modal = document.getElementById('addtocartmodal');
    modal.style.display = 'none';
}

function showLoginAlertModal() {
    var modal = document.getElementById('loginAlertModal');
    modal.style.display = 'block';
    setTimeout(closeLoginAlertModal, 3000);
}

function closeLoginAlertModal() {
    var modal = document.getElementById('loginAlertModal');
    modal.style.display = 'none';
}

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

function addToCart(productId) {
    <?php if (!isset($_SESSION['user_id'])): ?>
        showLoginAlertModal();
        return;
    <?php endif; ?>

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                try {
                    var response = JSON.parse(this.responseText);
                    if (response.message === "Item has been added to the cart.") {
                        showModal("Item has been added to the cart.");
                        updateCartCount();
                    } else if (response.message === "This item is already in your cart.") {
                        showModal("This item is already in your cart.");
                    } else {
                        showModal("An error occurred. Please try again later.");
                    }
                } catch (error) {
                    showModal("An error occurred. Please try again later.");
                }
            } else {
                showModal("An error occurred. Please try again later.");
            }
        }
    };
    xhr.open("GET", "product_details.php?add_to_cart=" + productId, true);
    xhr.send();
}
</script>


<div class="page-title" style="margin-top: 0;">
    <h1> Featured Furniture </h1>
    <p>Premium Hand Made Palochina Wood Furnitures</p>
</div>

<!-- Category Navigation -->
<nav id="catnav" class="category-nav">
    <button class="dropdown-toggle" id="dropdownButton" onclick="toggleDropdown()">Categories</button>
    <ul id="categoryList" class="category-list">
        <li><a href="product.php" class="nav-link">All</a></li>
        <?php
        $con = mysqli_connect('localhost', 'root', '', 'rswoodworks');
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }

        $sql = "SELECT category_title FROM categories WHERE category_title != 'Planks'";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li><a href='product.php?category=" . urlencode($row['category_title']) . "' class='nav-link'>" . htmlspecialchars($row['category_title']) . "</a></li>";
            }
        } else {
            echo "<li><span class='no-categories'>No categories found</span></li>";
        }
        ?>
    </ul>
</nav>
<script>
function toggleDropdown() {
    var menu = document.getElementById('catnav');
    menu.classList.toggle('show-menu');
}
</script>

<!-- Products Section -->
<section id="product1" class="section-p1" style="margin-top: 50px; margin-bottom:100px;">
    <div class="pro-container">
        <?php 
        if (isset($_GET['add_to_cart'])) {
            $productId = intval($_GET['add_to_cart']);
            handleCartRequest($productId);
        }

        if (isset($_GET['category'])) {
            $category = $_GET['category'];
            get_unique_categories($category);
        } else {
            getNonPlankProducts();
        }
        ?>
</div>

    <!-- Pagination -->
    <?php
    echo "<div class='pagination'>";
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $pagination_query = "SELECT COUNT(*) AS total_products FROM `products`";
    if (isset($_GET['category'])) {
        $category = mysqli_real_escape_string($con, $_GET['category']);
        $pagination_query .= " WHERE `category_title` = '$category'";
    }
    $pagination_result = mysqli_query($con, $pagination_query);
    $pagination_data = mysqli_fetch_assoc($pagination_result);
    $total_products = $pagination_data['total_products'];
    $total_pages = ceil($total_products / 9);

    for ($page = 1; $page <= $total_pages; $page++) {
        if ($page == $current_page) {
            echo "<span class='page-link active'>$page</span>";
        } else {
            echo "<a href='?page=$page" . (isset($_GET['category']) ? "&category=" . urlencode($_GET['category']) : "") . "' class='page-link'>$page</a>";
        }
    }
    echo "</div>";
    ?>


<div id="carouselExampleCaptions" class="carousel slide" style="margin-top: 20px; margin-bottom: 40px;">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="media/furbanner.jpg" class="carousel-img d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>WELCOME!</h5>
                <p>Shop now at RS WOODWORKS</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="media/bluebanner.jpg" class="carousel-img d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Palochina Wood Furnitures</h5>
                <p>Hand crafted with the best quality!</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="..." class="carousel-img d-block w-100" alt="...">
            <div class="carousel-caption d-none d-md-block">
                <h5>Third slide label</h5>
                <p>Some representative placeholder content for the third slide.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<div class="page-title" style="margin-top: 0;">
    <h1> Palochina Planks</h1>
    <p>Premium Palochina Wood Planks Bundles</p>
</div>

<section id="product1" class="section-p1" style="margin-top: 50px; margin-bottom:100px;">
    <div class="pro-container">
        <?php 
        if (isset($_GET['add_to_cart'])) {
            $productId = intval($_GET['add_to_cart']);
            handleCartRequest($productId);
        }

        if (isset($_GET['category'])) {
            $category = $_GET['category'];
            get_unique_categories($category);
        } else {
            getPlankProducts();
        }
        ?>
</div>
</section>


<!-- Contact us -->
<section id="contact-us" class="contact-us-section scroll-transition" data-aos="fade-up" data-aos-duration="1500" style="margin-bottom: 0;">
    <h2>Contact Us</h2>
<section id="contact" style="margin-bottom: 0;">
    <div class="container">
		<div class="row">
			<!-- Left Column (Contact Info and Map) -->
			<div class="col-lg-6 col-md-6">
				<div class="contact-info">
					<h4>Contact Details</h4>
					<ul>
						<li><strong>Email:</strong> rswoodworks@gmail.com</li>
						<li><strong>Phone:</strong> 09206218680</li>
						<li><strong>Address:</strong> Addas Greenfields Phase 2 Bacoor City Cavite.</li>
					</ul>
				</div>
				
				<div class="map-container">
				<p style="text-align: left; margin: 0;">Find us here:</p>
					<iframe style="height: 330px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3864.3085216292066!2d120.96428281052486!3d14.409370085996533!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d37a05afdd09%3A0x54986b0682c4438!2sAddas%20Greenfields%20Phase%202!5e0!3m2!1sen!2sph!4v1732659867312!5m2!1sen!2sph" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
				</div>
			</div>
			
		</div>
		<!-- Right Column (Contact Form) -->
		<div class="col-lg-6 col-md-6">
				<div class="contact-form">
					<h2>Send Us a Message</h2>
					<form action="send_email.php" method="post">
						<div class="input-box">
							<input type="text" id="name" name="name" required="required">
							<span>Full Name</span>
						</div>
						<div class="input-box">
							<input type="email" id="email" name="email" required="required">
							<span>Email</span>
						</div>
						<div class="input-box">
							<input type="text" id="contact" name="contact" required="required">
							<span>Contact No.</span>
						</div>
						<div class="input-box">
							<textarea id="message" name="message" required="required"></textarea>
							<span>How can we help you?</span>
						</div>
						<div class="input-box">
							<input type="submit" name="submit" value="Send Message">			
						</div>
					</form>
			    </div>
            </div>
        </div>
</section>
</section>

<style>
.contact-info, .contact-form {
	transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
}

.contact-info:hover, .contact-form:hover {
	transform: translateY(-10px);
	opacity: 0.9;
}

.map-container iframe {
	transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
}

.map-container iframe:hover {
	transform: scale(1.05);
	opacity: 0.9;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</main>
<?php include 'includes/footer.php'; ?>
