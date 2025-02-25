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
            <button id="viewToggleButton">Switch to Side View</button>
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
                            <td>Material: <span>Palochina Wood</span></td>s
                            <td>Dimension: <span id="dimensionCell"></span></td>
                        </tr>
                        <tr>
                            <td>Color: <span id="colorTableCell"></span></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Edges: <span id="edgesTableCell"></span></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="right-container">
        <h1>Custom Palochina Bench</h1>
            <div class="label">
                <h4>Dimensions: <span id="dimensionText"></span></h4>
                <div class="dimension-container">
                    <div class="dimension">
                        <label for="lengthInput">Length (cm):</label>
                        <input type="number" id="lengthInput" value="150" min="50" max="200">
                    </div>
                    <div class="dimension">
                        <label for="widthInput">Width (cm):</label>
                        <input type="number" id="widthInput" value="80" min="40" max="100">
                    </div>
                    <div class="dimension">
                        <label for="heightInput">Height (cm):</label>
                        <input type="number" id="heightInput" value="75" min="40" max="120">
                    </div>
                </div>
            </div>
            <div class="label">
                <h4>COLOR: <span id="colorText"></span></h4>
            </div>
            <div class="color-options">
                <div class="color-option selected-color" data-color="normal">
                    <img src="media/normal.png" alt="Brown">
                    <span>Normal</span>
                </div>
                <div class="color-option" data-color="oak">
                    <img src="media/oak.png" alt="oak">
                    <span>Oak</span>
                </div>
                <div class="color-option" data-color="maple">
                    <img src="media/maple.png" alt="maple">
                    <span>Maple</span>
                </div>
                <div class="color-option" data-color="darkwalnut">
                    <img src="media/darkwalnut.png" alt="Dark Walnut">
                    <span>Dark Walnut</span>
                </div>
            </div>
            <div class="label">
                <h4>EDGES: <span id="edgesText"></span></h4>
            </div>
            <div class="design-options-container">
                <div class="design-options">
                    <div class="design-option selected-design" data-edge="normal">
                        <img src="" alt="normal">
                        <span>Normal</span>
                    </div>
                    <div class="design-option" data-edge="swiss">
                        <img src="" alt="swiss">
                        <span>Swiss</span>
                    </div>
                </div>
            </div>
            <div class="custom-details">
                <form id="checkout" method="post" action="transaction/configpayment.php">
                    <h4>Product Details</h4>
                    <p>Elevate your home decor with our beautiful Palochina Wood Bench. Crafted from sustainable Palochina wood,
                this bench offers both style and durability. Its simple yet elegant design makes it a perfect addition
                to any room, whether it's your living room, bedroom, or entryway.</p>
                    <p>Features:
                        Material: Palochina Wood<br>
                        Color Options: Dark Walnut, Oak, Maple<br>
                        Edges Styles: Swiss Edge, Normal 
                        <br> <br>
                        Dimensions:  <br>
                        Length: min(50cm)-max(200cm) <br>
                        width: min(40cm)-max(100cm) <br>
                        height: min(40cm)-max(120cm)
                    </p>
                    <br>
                    <span name="product_price" id="product_price">₱1200</span>
                    <br>
                    <input type="number" name="quantity" id="quantityInput" value="1" min="1">
                    <input type="hidden" name="amount" id="amountInput" value="1200">
                    <button type="submit" id="checkout"><i class='bi bi-bag-plus-fill'></i>Check Out</button>
                    <input type="hidden" name="product_name" value="Custom Dining Table">
                    <input type="hidden" name="custom_image" id="product_image"><!-- Replace 'path/to/custom_image.jpg' with the actual path -->
                    <input type="hidden" name="color" id="selectedColor">
                    <input type="hidden" name="design" id="selectedDesign">
                    <input type="hidden" name="product_price" id="product_price_input" value="1200">
                </form> 
            </div>
        </div>
    </div>
</section>
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
 
   
<!-- JavaScript -->
<script src="2dconfig.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        let isFrontView = true;

        // Furniture images based on color and design
        const furnitureImages = {
            normal: {
                normal: {
                    front: 'media/normalnormal.png',
                    side: 'media/normalnormal_side.png'
                },
                oak: {
                    front: 'media/normaloak.png',
                    side: 'media/normaloak_side.png'
                },
                maple: {
                    front: 'media/normalred.png',
                    side: 'media/normalred_side.png'
                },
                darkwalnut: {
                    front: 'media/normalbrown.png',
                    side: 'media/normalbrown_side.png'
                }
            },
            swiss: {
                normal: {
                    front: 'media/swissnormal.png',
                    side: 'media/swissnormal_side.png'
                },
                oak: {
                    front: 'media/swissoak.png',
                    side: 'media/swissoak_side.png'
                },
                maple: {
                    front: 'media/swissred.png',
                    side: 'media/swissred_side.png'
                },
                darkwalnut: {
                    front: 'media/swissbrown.png',
                    side: 'media/swissbrown_side.png'
                }
            }
        };

        // Function to draw the furniture on the canvas
        function drawFurniture(color, edge) {
            const img = new Image();
            const view = isFrontView ? 'front' : 'side';
            const imgSrc = furnitureImages[edge][color][view];
            console.log("Image Source:", imgSrc);
            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
            };
            img.onerror = function() {
                console.error("Error loading image:", img.src);
            };
            img.src = imgSrc;
        }

        // Initial drawing
        drawFurniture('normal', 'normal');

        // Function to update the table details
        function updateTableDetails() {
            const selectedColor = document.querySelector('.selected-color span').textContent;
            const selectedEdge = document.querySelector('.selected-design span').textContent;
            const length = document.getElementById('lengthInput').value;
            const width = document.getElementById('widthInput').value;
            const height = document.getElementById('heightInput').value;
            const colorTableCell = document.getElementById('colorTableCell');
            const edgesTableCell = document.getElementById('edgesTableCell');
            const dimensionCell = document.getElementById('dimensionCell');

            if (colorTableCell && edgesTableCell && dimensionCell) {
                colorTableCell.textContent = selectedColor;
                edgesTableCell.textContent = selectedEdge;
                dimensionCell.textContent = length + 'x' + width + 'x' + height;
            }
        }

       // Function to update the displayed price
        function updateDisplayedPrice() {
            const length = parseInt(document.getElementById('lengthInput').value);
            const width = parseInt(document.getElementById('widthInput').value);
            const height = parseInt(document.getElementById('heightInput').value);
            const totalPrice = document.getElementById('product_price');
            const amountInput = document.getElementById('amountInput');
            const basePrice = 1200;

            // Calculate the total size
            const totalSize = length + width + height;

            // Calculate the price change based on the total size
            const priceChange = Math.floor(totalSize / 5) * 5; // Round down to the nearest multiple of 5

            // Update the total price with the base price and the price change
            if (totalPrice && amountInput) {
                const finalPrice = basePrice + priceChange;
                totalPrice.textContent = '₱' + finalPrice;
                amountInput.value = finalPrice * 100; // Convert to centavos for PayMongo
            }
        }

        // Event listeners to trigger price updates when dimensions change
        document.getElementById('lengthInput').addEventListener('input', updateDisplayedPrice);
        document.getElementById('widthInput').addEventListener('input', updateDisplayedPrice);
        document.getElementById('heightInput').addEventListener('input', updateDisplayedPrice);
        // Update canvas when color option is clicked
        document.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelector('.selected-color').classList.remove('selected-color');
                option.classList.add('selected-color');
                updateTableDetails();
                updateDisplayedPrice();
                drawFurniture(option.getAttribute('data-color'), document.querySelector('.selected-design').getAttribute('data-edge')); // Call drawFurniture here
                updateColorText();
            });
        });

        // Update canvas when design option is clicked
        document.querySelectorAll('.design-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelector('.selected-design').classList.remove('selected-design');
                option.classList.add('selected-design');
                updateTableDetails();
                updateDisplayedPrice();
                drawFurniture(document.querySelector('.selected-color').getAttribute('data-color'), option.getAttribute('data-edge')); // Call drawFurniture here
                updateEdgesText();
            });
        });

        // Function to update the chosen options
        function updateChosenOptions() {
            const selectedColor = document.querySelector('.selected-color span').textContent;
            const selectedEdge = document.querySelector('.selected-design span').textContent;
            const length = document.getElementById('lengthInput').value;
            const width = document.getElementById('widthInput').value;
            const height = document.getElementById('heightInput').value;
            const dimensionText = `${length}cm x ${width}cm x ${height}cm`; // Format dimensions
            
            document.getElementById('colorText').textContent = selectedColor;
            document.getElementById('edgesText').textContent = selectedEdge;
            document.getElementById('dimensionCell').textContent = dimensionText;
        }

        // Function to update the dimensions text
        function updateDimensionsText() {
            const length = document.getElementById('lengthInput').value;
            const width = document.getElementById('widthInput').value;
            const height = document.getElementById('heightInput').value;
            const dimensionTextSpan = document.getElementById('dimensionText');
            if (dimensionTextSpan) {
                dimensionTextSpan.textContent = `${length}cm x ${width}cm x ${height}cm`;
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

        // Function to update the edges text
        function updateEdgesText() {
            const selectedEdge = document.querySelector('.selected-design span').textContent;
            const edgesTextSpan = document.getElementById('edgesText');
            if (edgesTextSpan) {
                edgesTextSpan.textContent = selectedEdge;
            }
        }

        // Event listeners to trigger the text updates
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', function() {
                updateDimensionsText();
                updateChosenOptions();
            });
        });

        // Event listener for the view toggle button
        document.getElementById('viewToggleButton').addEventListener('click', function() {
            isFrontView = !isFrontView;
            this.textContent = isFrontView ? 'Switch to Side View' : 'Switch to Front View';
            drawFurniture(document.querySelector('.selected-color').getAttribute('data-color'), document.querySelector('.selected-design').getAttribute('data-edge'));
        });
    });
</script>
<?php include 'includes/footer.php'?>

</body>
</html>