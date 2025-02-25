<?php
include './includes/header.php';
?>
<!-- 2d configurator -->
<section id="config">
    <div class="container">
        <div class="left-container">
            <div class="image-container">
                <canvas id="canvas" style="max-width: 90%; max-height: 100%;"></canvas>
            </div>
            <span style="margin-left: 0; font-size: 11px; font-style: italic; margin-top: 0; color: #333">*Original product may vary from image*</span>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Details</th>
                            <th>Dimension</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Material: <span>Palochina Wood</span></td>
                            <td>Dimension: <span id="dimensionCell"></span></td>
                        </tr>
                        <tr>
                            <td>Color: <span id="colorTableCell"></span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Legs type: <span id="legTableCell"></span></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="right-container">
            <h1>Custom Dining Table</h1>
            <div class="label">
                <h4>SIZE: <span id="sizeText"></span></h4>
            </div>
            <div class="size-options">
                <button id="small"  data-size="small"><span>Square (32&quot; X 32&quot;)</span></button>
                <button id="medium" data-size="medium"><span>Medium (42&quot; X 28&quot;)</span></button>
                <button id="large" data-size="large"><span>Large (62&quot; X 32&quot;)</span></button>
            </div> 

            <div class="label">
                <h4>COLOR: <span id="colorText"></span></h4>
            </div>
            <div class="color-options">
                <div class="color-option selected-color" data-color="brown">
                    <img src="media/brown.png" alt="Brown">
                    <span>Light Oak</span>
                </div>
                <div class="color-option" data-color="white">
                    <img src="media/white.png" alt="White">
                    <span>White/brown</span>
                </div>
                <div class="color-option" data-color="lightbrown">
                    <img src="media/lightbrown.png" alt="Dark Walnut">
                    <span>Dark Walnut</span>
                </div>
            </div>
            <div class="label">
                <h4>LEG: <span id="legText"></span></h4>
            </div>
            <div class="design-options-container">
                <div class="design-options">
                    <div class="design-option" data-leg="leg1">
                        <img src="media/leg1-transformed.png" alt="Leg 1">
                        <span>Wide and square</span>
                    </div>
                    <div class="design-option" data-leg="leg2">
                        <img src="media/leg2-transformed.png" alt="Leg 2">
                        <span>Two-way tapered leg</span>
                    </div>
                </div>
            </div>
            <div class="custom-details">
                <form id="checkout" method="post" action="custom_checkout.php">
                    <h4>Product Details</h4>
                    <p>Elevate your dining experience with our custom Palochina wood dining table, meticulously 
                        crafted to suit your style and space. Palochina wood, known for its durability 
                        and distinctive grain patterns, adds warmth and character to any dining area.
                    </p>
                    <p>Features:
                        Material: Palochina Wood<br>
                        Finish Options: Dark brown, White/brown, Dark Walnut<br>
                        Size Options: Small (32" x 32"), Medium (42" x 28"), Large (62" x 32")<br>
                        Leg Styles: Wide and Square, Two-Way Tapered, Custom Designs Available</p>
                    <br>
                    <span name="product_price" id="product_price">₱1000</span>
                    <br>
                    <input type="number" name="quantity" id="quantityInput" value="1" min="1">
                    <button type="submit" id="checkout"><i class='bi bi-bag-plus-fill'></i>Check Out</button>
                    <input type="hidden" name="ip_address" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
                    <input type="hidden" name="product_name" value="Custom Dining Table">
                    <input type="hidden" name="custom_image" id="product_image"><!-- Replace 'path/to/custom_image.jpg' with the actual path -->
                    <input type="hidden" name="size" id="selectedSize">
                    <input type="hidden" name="color" id="selectedColor">
                    <input type="hidden" name="design" id="selectedDesign">
                    <input type="hidden" name="product_price" id="product_price_input" value="1000">
                    <!-- Add to Cart button -->
                </form> 
            </div>
        </div>
    </div>
</section>

<!-- faqsection -->
<section id="faq" style="margin-bottom: 100px;">
    <div class="faqimage">
        <img src="media/faq.svg" width="200px" height="200px">
        <p>Frequently Asked Questions</p>
    </div>
    <div id="accordion">
        <!-- Your accordion content here -->
        <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Can I do a special request for the custom furniture?
                    </button>
                </h5>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    You can send us an email about the details that you want in your custom furniture whenever you order.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        How long is the delivery time for the custom furnitures?
                    </button>
                </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                The estimated delivery time is 2-4 weeks depending on the order in the configurator. For special requests, the delivery time may be extended by 1-3 weeks, depending on the additional work required.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingThree">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Can I return my order after the delivery?
                    </button>
                </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                As we don't maintain stock and craft each table uniquely for our clients, we regret to inform you that we're unable to accommodate returns for custom-made furniture. We appreciate your comprehension in this matter. But if the custom order is broken and not in good shape we can talk about it.
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- Start We Help Section -->
<div class="we-help-section">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="imgs-grid">
                    <div class="grid grid-1"><img src="media/furnituresss.webp" alt="Untree.co"></div>
                    <div class="grid grid-2"><img src="media/woodplanks2.webp" alt="Untree.co"></div>
                    <div class="grid grid-3"><img src="media/woodplanks3.webp" alt="Untree.co"></div>
                </div>
            </div>
            <div class="col-lg-5 ps-lg-5">
                <h2 class="section-title mb-4">We Help You Make Modern Furniture Design</h2>
                <p>At RS WoodWorks, we specialize in guiding you through the world of modern furniture design. Our curated collection showcases contemporary elegance and enduring craftsmanship. Let us elevate your living space with pieces that seamlessly blend innovation and sophistication, reflecting your distinctive taste and style.</p>

                <ul class="list-unstyled custom-list my-4">
                
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End We Help Section -->

<!-- /* Contact Us Section */ -->
<section id="contact-us" class="contact-us-section">
<style>
.contact-us-section {
  padding: 60px 0;
  background-color: #f9f9f9;
  color: #333;
}

/* Container */
.contact-us-section .container {
  max-width: 1200px;
  margin: 0 auto;
}

/* Title & Paragraph */
.contact-us-section h2 {
  font-size: 32px;
  margin-bottom: 15px;
  font-weight: bold;
}

.contact-us-section p {
  font-size: 16px;
  color: #777;
}

/* Contact Info */
.contact-info {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.contact-info h4 {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 15px;
}

.contact-info ul {
  list-style: none;
  padding-left: 0;
}

.contact-info ul li {
  font-size: 16px;
  margin-bottom: 10px;
}

.contact-info ul li strong {
  font-weight: bold;
  color: #0056b3;
}

/* Map Container */
.map-container {
  margin-top: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .contact-us-section .row {
    flex-direction: column;
    align-items: center;
  }

  .contact-us-section .col-md-6 {
    width: 100%;
    margin-bottom: 30px;
  }

  .map-container iframe {
    height: 250px;
  }
}
</style>
        <div class="container">
            <div class="row">
            <!-- Left Column (Contact Info) -->
            <div class="col-md-6">
                <h2>Contact Us</h2>
                <p>If you have any questions, feel free to get in touch with us through the contact information below.</p>

                <div class="contact-info">
                <h4>Contact Details</h4>
                <ul>
                    <li><strong>Email:</strong> rswoodworks@gmail.com</li>
                    <li><strong>Phone:</strong> 09206218680</li>
                    <li><strong>Address:</strong> Addas Greenfields Phase 2 Bacoor City Cavite.</li>
                </ul>
                </div>
            </div>
            
            <!-- Right Column (Map) -->
            <div class="col-md-6">
                <h3>Our Location</h3>
                <p>Find us here:</p>
                
                <!-- Embed Google Map -->
                <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3864.3085216292066!2d120.96428281052486!3d14.409370085996533!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d37a05afdd09%3A0x54986b0682c4438!2sAddas%20Greenfields%20Phase%202!5e0!3m2!1sen!2sph!4v1732659867312!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            </div>
        </div>
</section>


<!-- JavaScript -->
<script src="2dconfig.js"></script>

<script>
    // JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');

    const furnitureImages = {
        brown: {
            small: {
                leg1: 'media/small1brown.jpg',
                leg2: 'media/small2brown.jpg',
            },
            medium: {
                leg1: 'media/medium1brown.jpg',
                leg2: 'media/medium2brown.jpg',
            },
            large: {
                leg1: 'media/large1brown.jpg',
                leg2: 'media/large2brown.jpg',
            }
        },
        white: {
            small: {
                leg1: 'media/small1white.jpg',
                leg2: 'media/small2white.jpg',
            },
            medium: {
                leg1: 'media/medium1white.jpg',
                leg2: 'media/medium2white.jpg',
            },
            large: {
                leg1: 'media/large1white.jpg',
                leg2: 'media/large2white.jpg',
            }
        },
        lightbrown: {
            small: {
                leg1: 'media/small1light.jpg',
                leg2: 'media/small2light.jpg',
            },
            medium: {
                leg1: 'media/medium1light.jpg',
                leg2: 'media/medium2light.jpg',
            },
            large: {
                leg1: 'media/large1light.jpg',
                leg2: 'media/large2light.jpg',
            }
        }
    };

    function drawFurniture(color, size, design) {
        const img = new Image();
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
        };
        img.onerror = function() {
            console.error("Error loading image:", img.src);
        };
        const imgSrc = furnitureImages[color][size][design];
        img.src = imgSrc;
    }
    // Function to update the canvas
    function updateCanvas() {
        const color = document.querySelector('.selected-color').getAttribute('data-color');
        const size = document.querySelector('.selected-size').getAttribute('data-size');
        const design = document.querySelector('.selected-design').getAttribute('data-leg');
        drawFurniture(color, size, design);
    }

    // Initial drawing
    drawFurniture('brown', 'small', 'leg1');



  // Function to update details in the table
  function updateDetails() {
        updateTableDetails(); // Call the updateTableDetails function
        updateDisplayedPrice(); // Call the function to update the displayed price
        updateSizeText(); // Call the function to update the size text
        updateColorText(); // Call the function to update the color text
        updateLegText(); // Call the function to update the leg text
        updateCanvas(); // Call the function to update the canvas
    }

    // Function to update the size text
    function updateSizeText() {
        const selectedSize = document.querySelector('.selected-size span').textContent;
        const sizeTextSpan = document.getElementById('sizeText');
        if (sizeTextSpan) {
            sizeTextSpan.textContent = selectedSize;
        }
    }

    // Function to update the color text
    function updateColorText() {
        const selectedColor = document.querySelector('.selected-color span').textContent;
        const colorTextSpan = document.getElementById('colorText');
        if (colorTextSpan) {
            colorTextSpan.textContent = selectedColor;
        }
    }

    // Function to update the leg text
    function updateLegText() {
        const selectedDesign = document.querySelector('.selected-design span').textContent;
        const legTextSpan = document.getElementById('legText');
        if (legTextSpan) {
            legTextSpan.textContent = selectedDesign;
        }
    }

    // Function to update the table details
    function updateTableDetails() {
        const selectedColor = document.querySelector('.selected-color span').textContent;
        const selectedSize = document.querySelector('.selected-size span').textContent;
        const selectedDesign = document.querySelector('.selected-design span').textContent;
        const colorTableCell = document.getElementById('colorTableCell');
        const dimensionCell = document.getElementById('dimensionCell');
        const legTableCell = document.getElementById('legTableCell');

        if (colorTableCell && dimensionCell && legTableCell) {
            colorTableCell.textContent = selectedColor;
            dimensionCell.textContent = selectedSize;
            legTableCell.textContent = selectedDesign;
        }
    }

    // Event listeners for size options
    const sizeButtons = document.querySelectorAll('.size-options button');
    sizeButtons.forEach(button => {
        button.addEventListener('click', function() {
            sizeButtons.forEach(btn => btn.classList.remove('selected-size'));
            this.classList.add('selected-size');
            updateDetails(); // Update details when size option is clicked
        });
    });

    // Event listeners for color options
    const colorOptions = document.querySelectorAll('.color-option');
    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            colorOptions.forEach(opt => opt.classList.remove('selected-color'));
            this.classList.add('selected-color');
            updateDetails(); // Update details when color option is clicked
        });
    });

    // Event listeners for design options
    const designOptions = document.querySelectorAll('.design-option');
    designOptions.forEach(option => {
        option.addEventListener('click', function() {
            designOptions.forEach(opt => opt.classList.remove('selected-design'));
            this.classList.add('selected-design');
            updateDetails(); // Update details when design option is clicked
        });
    });

    // Function to calculate and update product price
    function updateDisplayedPrice() {
        const priceSpan = document.getElementById('product_price');
        const priceInput = document.getElementById('product_price_input');
        if (priceSpan && priceInput) {
            const price = calculatePrice();
            priceSpan.textContent = '₱' + price.toFixed(2);
            priceInput.value = price.toFixed(2);
        }
    }
        // function to update product price
    function calculatePrice() {
        let basePrice = 1000;
        const selectedSize = document.querySelector('.selected-size').getAttribute('data-size');
        if (selectedSize === 'medium') {
            basePrice += 450;
        } else if (selectedSize === 'large') {
            basePrice += 930;
        }
        const selectedColor = document.querySelector('.selected-color').getAttribute('data-color');
        if (selectedColor === 'white') {
            basePrice += 50;
        } else if (selectedColor === 'lightbrown') {
            basePrice += 60;
        }
        const selectedDesign = document.querySelector('.selected-design').getAttribute('data-leg');
        if (selectedDesign === 'leg2') {
            basePrice += 400;
        }
        const quantity = parseInt(document.getElementById('quantityInput').value);
        const totalPrice = basePrice * quantity;
        return totalPrice;
    }

    // Event listener for quantity input change
    const quantityInput = document.getElementById('quantityInput');
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            updateDisplayedPrice();
        });
    }

    // Event listener for "Add to Cart" button
    const addToCartButton = document.getElementById('addToCartButton');
    if (addToCartButton) {
        addToCartButton.addEventListener('click', function() {
            updateProductImage();
        });
    }

    // Function to capture canvas image as data URL and update hidden input field
    function updateProductImage() {
        const productImageInput = document.getElementById('product_image');
        if (productImageInput) {
            const canvasImage = captureCanvasImage();
            productImageInput.value = canvasImage;
        }
    }

    function captureCanvasImage() {
        const canvas = document.getElementById('canvas');
        const canvasDataUrl = canvas.toDataURL('image/jpeg');
        return canvasDataUrl;
    }

    // Function to draw furniture on the canvas
    function drawFurniture(color, size, design) {
        const img = new Image();
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
        };
        img.onerror = function() {
            console.error("Error loading image:", img.src);
        };
        const imgSrc = furnitureImages[color][size][design];
        img.src = imgSrc;
    }

    // Initial update of displayed price and canvas
    updateDisplayedPrice();
    updateCanvas();
});

</script>


</body>
</html>
<?php include 'includes/footer.php';?>