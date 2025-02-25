<?php 
// Start the session
include __DIR__ . '/functions.php';
include __DIR__ . '/connection.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RS WOODWORKS</title>
    <link rel="stylesheet" href=" https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="css/2dconfig.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/notif.css">
    <link rel="stylesheet" href="css/chatroom.css">
    <link rel="stylesheet" href="css/details.css">
    <script src="https://js.stripe.com/v3/"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="../css/userprofile.css">
    <style>
        .search-result-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 5px;
            cursor: pointer;
        }
        .search-result-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
            
        }
        .search-result-item .details {
            flex-grow: 1;
        }
        .search-result-item .details h5 {
            margin: 0;
            font-size: 1rem;
        }
        .search-result-item .details p {
            margin: 0;
            font-size: 0.875rem;
            color: #666;
        }
        #searchResults {
            width: 100%;
            max-width: 800px;
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<style>
</style>
<body>
   <!-- Navbar -->
   <nav class="navbar navbar-expand-lg shadow-sm py-3">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="home.php">
            <img src="media/rswoodlogo.png" alt="RS WOODWORKS Logo" class="logo-img" style="width: 100%; height: 70px;">
        </a>
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style=" font-size: 20px; color: #00094b;"><i class="fa-solid fa-bars"></i></span>
        </button>
        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto d-flex align-items-center"> 
                <!-- Home, Products, About, Contact Links -->
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="product.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="home.php#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="home.php#contact-us">Contact</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFurniture" role="button" data-bs-toggle="dropdown" aria-expanded="false">Furniture Configurator</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownFurniture">
                        <!-- Dropdown links -->
                        <li><a class="dropdown-item" href="2dconfig.php">Dining Table</a></li>
                        <li><a class="dropdown-item" href="benchconfig.php">Bench</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto d-flex">
                <!-- Search Bar -->
                <li class="nav-item mx-2 position-relative" style="max-width: 600px;">
                    <form class="d-flex" method="GET" action="product_details.php">
                        <input class="form-control" type="search" autocomplete="off" placeholder="Search for products" aria-label="Search" name="search" id="searchInput" onkeyup="searchProducts()" style="height: 50px; font-size: 1.2rem; padding: 10px 15px; width: 270px; border-radius: 25px;">
                    </form>
                    <!-- Floating search results container -->
                    <div id="searchResults" class="dropdown-menu w-100" style="position: absolute; z-index: 10;"></div>
                </li>

                <script>
                    function searchProducts() {
                        let searchQuery = document.getElementById('searchInput').value;

                        // Show results only if the search query is not empty
                        if (searchQuery.length > 0) {
                            $.ajax({
                                url: 'search.php',
                                method: 'GET',
                                data: { query: searchQuery },
                                success: function(data) {
                                    let resultsContainer = document.getElementById('searchResults');
                                    resultsContainer.style.display = 'block';
                                    resultsContainer.innerHTML = data;
                                }
                            });
                        } else {
                            document.getElementById('searchResults').style.display = 'none';
                        }
                    }

                    $(document).on('click', '.search-result-item', function() {
                        let productId = $(this).data('id');
                        window.location.href = 'product_details.php?product_id=' + productId;
                    });

                    // Hide the dropdown when clicking outside
                    $(document).click(function(e) {
                        if (!$(e.target).closest('#searchInput').length) {
                            $('#searchResults').hide();
                        }
                    });
                </script>
                <ul class="nav-icons d-flex align-items-center">
                    <?php if (isset($_SESSION['username']) || isset($_SESSION['admin_username'])): ?>
                    <!-- Chat Icon -->
                    <li class="nav-item mx-2">
                        <a class="nav-link d-flex align-items-center" href="javascript:void(0);" id="chatModalButton">
                            <i class="fas fa-comments"></i>
                            <span id="newMessageNotification" class="badge bg-danger">
                                <?php echo $newMessageCount; ?>
                            </span>
                        </a>
                    </li>
                    <!-- Chat Modal -->
                    <div class="modal" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" >
                            <div class="modal-content" style="width: auto; max-width: 100%; height: auto; padding: 0; margin: 0;">
                                <div class="modal-body" id="chatModalBody" style="width: auto; overflow-y: auto;">
                                    <!-- Chatroom content will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.getElementById('chatModalButton').addEventListener('click', function() {
                            let xhr = new XMLHttpRequest();
                            xhr.open('GET', 'chatroom/user_chatroom.php', true);
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState == 4 && xhr.status == 200) {
                                    document.getElementById('chatModalBody').innerHTML = xhr.responseText;
                                    let chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
                                    chatModal.show();
                                    // Initialize chatroom functions
                                    initializeChatroom();
                                }
                            };
                            xhr.send();
                        });

                        const other_id = 1; // This should be set to the admin's user ID

                        function fetchMessages() {
                            fetch("chatroom/fetch_message.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: "receiver_id=" + encodeURIComponent(other_id)
                            })
                            .then(response => response.text())
                            .then(data => {
                                document.getElementById("chat-box").innerHTML = data;
                                document.getElementById("chat-box").scrollTop = document.getElementById("chat-box").scrollHeight;
                            })
                            .catch(error => console.error("Error fetching messages:", error));
                        }

                        function sendMessage() {
                        const message = document.getElementById("chat-input").value;
                        if (message.trim() === "") return;

                        displayMessage(message, true); // Display user message immediately
                        document.getElementById("chat-input").value = "";

                        // Send the user's message
                        fetch("chatroom/send_message.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: `message=${encodeURIComponent(message)}&receiver_id=${other_id}&sender_type=user`
                        });
                        }

                        function sendFAQ(faqQuestion) {
                        // Display the user's question immediately in the chat
                        displayMessage(faqQuestion, true);

                        // Send the question to the server to appear in the history as a user message
                        fetch("chatroom/send_message.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: `message=${encodeURIComponent(faqQuestion)}&receiver_id=${other_id}&sender_type=user`
                        })
                        .then(() => {
                            // Get the automated response
                            const faqResponse = faqResponses[faqQuestion];

                            // Send the automated response to the server as an admin message
                            return fetch("chatroom/send_message.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: `message=${encodeURIComponent(faqResponse)}&receiver_id=${other_id}&sender_type=admin`
                            }).then(() => {
                                displayMessage(faqResponse, false); // Display admin reply
                            });
                        })
                        .catch(error => console.error("Error sending FAQ message:", error));
                        }


                        // Modified displayMessage function to apply 'admin' class for automated responses
                        function displayMessage(message, isUser) {
                        const chatBox = document.getElementById("chat-box");
                        const messageDiv = document.createElement("div");
                        messageDiv.className = `message ${isUser ? 'user' : 'admin'}`; // Admin class for FAQ replies
                        messageDiv.innerHTML = `<span>${message}</span><span class='timestamp'>Just now</span>`;
                        chatBox.appendChild(messageDiv);
                        chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the bottom
                        }
                        setInterval(fetchMessages, 3000);
                        document.addEventListener("DOMContentLoaded", fetchMessages);

                    </script>
                    
                        <!-- Notification Icon -->
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center" href="javascript:void(0);" id="notifModalButton">
                                <i class="bi bi-bell"></i>
                            </a>
                        </li>
                        
                        <!-- Notification Modal -->
                        <div id="notifModal" class="custom-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1050;">
                            <div class="custom-modal-content">
                            <button id="closeNotifModal" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 1.5rem; z-index: 1051;">&times;</button>
                                <div id="notif-container">
                                    <div id="notif-box" style="max-height: 400px; overflow-y: auto;"></div>
                                </div>
                            </div>
                        </div>

                        <script>
                            const closeNotifModal = document.getElementById("closeNotifModal");
                            closeNotifModal.addEventListener("click", () => {
                                notifModal.style.display = "none";
                            });
                        </script>
                        <script>
                            const notifModal = document.getElementById("notifModal");
                            const notifModalButton = document.getElementById("notifModalButton");

                            // Open modal
                            notifModalButton.addEventListener("click", () => {
                                notifModal.style.display = "block";
                                fetchNotifications(); // Fetch notifications when modal is opened
                            });

                            // Close modal when clicking outside
                            window.addEventListener("click", (e) => {
                                if (e.target === notifModal) {
                                    notifModal.style.display = "none";
                                }
                            });

                            function submitForm(action) {
                                const form = document.getElementById('notification-form');
                                const formData = new FormData(form);
                                fetch(action, {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        fetchNotifications(); // Refresh notifications
                                    } else {
                                        console.error(data.message);
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                            }

                            function fetchNotifications() {
                                fetch("notification/user_notif.php")
                                    .then(response => response.text())
                                    .then(data => {
                                        const notifBox = document.getElementById("notif-box");
                                        notifBox.innerHTML = data;
                                        // Reattach event listeners for the new content
                                        document.querySelectorAll('.notif-item input[type="checkbox"]').forEach(checkbox => {
                                            checkbox.addEventListener('change', () => {
                                                const selected = document.querySelectorAll('.notif-item input[type="checkbox"]:checked').length > 0;
                                                document.getElementById('removeSelectedButton').disabled = !selected;
                                            });
                                        });
                                    })
                                    .catch(error => console.error("Error fetching notifications:", error));
                            }

                            function viewNotification(message, date) {
                                const notifBox = document.getElementById("notif-box");
                                const encodedMessage = decodeURIComponent(message).replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
                                notifBox.innerHTML = `
                                    <div class="custom-modal" style="display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1050; display: flex; justify-content: center; align-items: center;">
                                        <div class="custom-modal-content" style="width: 80%; max-width: 800px; padding: 20px; border-radius: 10px; background-color: white; border: 2px solid #000;">
                                            <div id="notif-container">
                                                <div id="notif-box" style="max-height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
                                                    <h2 style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">Notification Details</h2>
                                                    <p style="word-wrap: break-word; text-align: left; border-bottom: 1px solid #ccc; padding-bottom: 10px;">${encodedMessage}</p>
                                                    <p style="text-align: left; color: gray; border-bottom: 1px solid #ccc; padding-bottom: 10px;">${date}</p>
                                                    <button class="btn btn-primary" onclick="fetchNotifications()">Back</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }

                            function selectAll() {
                                const checkboxes = document.querySelectorAll('#notif-box input[type="checkbox"]');
                                checkboxes.forEach(checkbox => checkbox.checked = true);
                            }

                            function markAsRead() {
                                const form = document.getElementById('notification-form');
                                const formData = new FormData(form);
                                fetch('notification/mark_read_notifications.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        fetchNotifications(); // Refresh notifications
                                    } else {
                                        console.error(data.message);
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                            }
                        </script>
                         <!-- User Dropdown -->
                         <li class="nav-item dropdown">
                            <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                <?php if (isset($_SESSION['username'])): ?>
                                    <li><a class="dropdown-item" href="user/profile.php">Profile</a></li>
                                <?php endif; ?>
                                <?php if (isset($_SESSION['admin_username'])): ?>
                                    <li><a class="dropdown-item" href="admin/adminpanel.php">Admin Panel</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="user/logout.php">Logout</a></li>
                            </ul>
                        </li>
                        <!-- Cart Icon -->
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center" href="cart.php">
                                <i class="bi bi-bag-check-fill"></i>
                                <span class="badge bg-danger"><?php cart_item(); ?></span>
                            </a>
                        </li>
                        
                    <?php else: ?>
                        <!-- Login Button -->
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-3 py-1" href="user/login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </ul>
        </div>
    </div>
</nav>

</body>
</html>

