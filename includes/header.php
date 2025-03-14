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
<body>
   <!-- Navbar -->
   <nav class="navbar navbar-expand-lg shadow-sm py-3">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="home.php">
            <img src="media/rswoodlogo.png" alt="RS WOODWORKS Logo" class="logo-img" style="width: 100%; height: 70px;">
        </a>
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="font-size: 20px; color: #00094b;">
                <i class="fa-solid fa-bars"></i>
            </span>
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFurniture" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Furniture Configurator
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownFurniture">
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
                        <?php
                        // Only for users will we count unread messages.
                        if (isset($_SESSION['user_id'])) {
                            $user_id = $_SESSION['user_id'];
                            // Count only unread messages (is_read = 0) for receiver_type 'user'
                            $stmt = $con->prepare("SELECT COUNT(*) as newMessageCount FROM messages WHERE receiver_id = ? AND receiver_type = 'user' AND is_read = 0");
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            $newMessageCount = $row['newMessageCount'];
                            $stmt->close();
                            if ($newMessageCount > 0): ?>
                                <!-- <span id="newMessageNotification" class="badge bg-danger">
                                    <?php echo $newMessageCount; ?>
                                </span> -->
                            <?php endif;
                        }
                        ?>
                    </a>
                </li>
                <script>
                    // Add one event listener to the chat icon.
                    document.getElementById('chatModalButton').addEventListener('click', function(e) {
                        e.preventDefault();
                        // Check if this is a user session.
                        var isUser = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
                        if (isUser) {
                            // Mark messages as read via AJAX only for users.
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', 'mark_read.php', true);
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    // Remove the notification badge once messages are marked as read.
                                    var badge = document.getElementById('newMessageNotification');
                                    if (badge) {
                                        badge.style.display = 'none';
                                    }
                                    loadChatModal();
                                }
                            };
                            xhr.send();
                        } else {
                            // For admins, just load the chat modal without marking messages as read.
                            loadChatModal();
                        }
                    });

                    // Function to load chat modal content.
                    function loadChatModal() {
                        var xhrChat = new XMLHttpRequest();
                        xhrChat.open('GET', 'chatroom/user_chatroom.php', true);
                        xhrChat.onreadystatechange = function() {
                            if (xhrChat.readyState === 4 && xhrChat.status === 200) {
                                document.getElementById('chatModalBody').innerHTML = xhrChat.responseText;
                                var chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
                                chatModal.show();
                                if (typeof initializeChatroom === "function") {
                                    initializeChatroom();
                                }
                            }
                        };
                        xhrChat.send();
                    }
                </script>

                

                <!-- Chat Modal -->
                <div class="modal" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content" style="width: auto; height: auto; padding: 0; margin: 0;">
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
                                    initializeChatroom();
                                }
                            };
                            xhr.send();
                        });

                        const other_id = 1; // Set to the admin's user ID

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
                            displayMessage(message, true);
                            document.getElementById("chat-input").value = "";
                            fetch("chatroom/send_message.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: `message=${encodeURIComponent(message)}&receiver_id=${other_id}&sender_type=user`
                            });
                        }

                        function sendFAQ(faqQuestion) {
                            displayMessage(faqQuestion, true);
                            fetch("chatroom/send_message.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: `message=${encodeURIComponent(faqQuestion)}&receiver_id=${other_id}&sender_type=user`
                            })
                            .then(() => {
                                const faqResponse = faqResponses[faqQuestion];
                                return fetch("chatroom/send_message.php", {
                                    method: "POST",
                                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                    body: `message=${encodeURIComponent(faqResponse)}&receiver_id=${other_id}&sender_type=admin`
                                }).then(() => {
                                    displayMessage(faqResponse, false);
                                });
                            })
                            .catch(error => console.error("Error sending FAQ message:", error));
                        }

                        function displayMessage(message, isUser) {
                            const chatBox = document.getElementById("chat-box");
                            const messageDiv = document.createElement("div");
                            messageDiv.className = `message ${isUser ? 'user' : 'admin'}`;
                            messageDiv.innerHTML = `<span>${message}</span><span class='timestamp'>Just now</span>`;
                            chatBox.appendChild(messageDiv);
                            chatBox.scrollTop = chatBox.scrollHeight;
                        }
                        setInterval(fetchMessages, 3000);
                        document.addEventListener("DOMContentLoaded", fetchMessages);
                    </script>
                    
                    <!-- Notification Icon -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="" id="notifModalButton">
                            <i class="bi bi-bell"></i>
                        </a>
                    </li>
                    
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
                            <span class="badge bg-danger" id="cartItemCount"><?php cart_item(); ?></span>
                        </a>
                    </li>
                    <script>
                        function updateCartItemCount() {
                            $.ajax({
                                url: 'cart_item_count.php',
                                method: 'GET',
                                success: function(data) {
                                    $('#cartItemCount').text(data);
                                }
                            });
                        }

                        // Update cart item count every 5 seconds
                        setInterval(updateCartItemCount, 5000);
                    </script>
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

<!-- Fetch notifications if user is logged in -->
<?php
    if(isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $notifications = [];
            $stmt = $con->prepare("SELECT id, message, type, created_at, is_read FROM notifications WHERE user_id = ? AND created_at >= NOW() - INTERVAL 30 DAY ORDER BY created_at DESC");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                 while($row = $result->fetch_assoc()){
                         $notifications[] = $row;
                 }
            }
            $stmt->close();
    } else {
            $notifications = [];
    }
    ?>
    
    <!-- Notifications Modal -->
    <div id="notifModal" class="custom-notif-modal">
        <div class="custom-notif-modal-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-bell"></i> Notifications</h5>
            <span class="close-notif" onclick="closeNotifModal()"><i class="fas fa-times"></i></span>
        </div>
        <div class="notif-button-row">
            <button onclick="selectAll()"><i class="fas fa-check-square"></i> Select All</button>
            <button onclick="submitForm('notification/remove_notifications.php', 'remove')"><i class="fas fa-trash-alt"></i> Remove Selected</button>
            <button onclick="submitForm('notification/mark_read_notifications.php', 'mark_read')"><i class="fas fa-check-circle"></i> Mark All as Read</button>
        </div>
        <div class="custom-notif-modal-body">
            <form id="notification-form" action="notification/remove_notifications.php" method="post">
                <?php if (!empty($notifications)): ?>
                    <?php foreach ($notifications as $notif): 
                        $sneak_peek = (strlen($notif['message']) > 70) ? substr($notif['message'], 0, 70) . '...' : $notif['message'];
                        $created_at = date('Y-m-d H:i:s', strtotime($notif['created_at']));
                    ?>
                    <div class="notif-item <?php echo !$notif['is_read'] ? 'unread' : ''; ?>">
                        <div>
                            <input type="checkbox" name="indexes[]" value="<?php echo $notif['id']; ?>">
                            <a href="javascript:void(0);" onclick="viewNotification('<?php echo rawurlencode($notif['message']); ?>', '<?php echo $created_at; ?>')">
                                <p><?php echo htmlspecialchars($sneak_peek, ENT_QUOTES, 'UTF-8'); ?></p>
                            </a>
                        </div>
                        <div class="notif-date"><?php echo $created_at; ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No notifications available.</p>
                <?php endif; ?>
            </form>
        </div>
        <div class="custom-notif-modal-footer">
            <button class="btn btn-secondary" onclick="closeNotifModal()"><i class="fas fa-times-circle"></i> Close</button>
        </div>
    </div>
    
    <!-- View Notification Details Modal -->
    <div id="viewNotifModal" class="custom-notif-modal">
        <div class="custom-notif-modal-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Notification Details</h5>
            <span class="close-notif" onclick="closeViewNotifModal()"><i class="fas fa-times"></i></span>
        </div>
        <div class="custom-notif-modal-body">
            <p id="full-notif-message"></p>
            <p id="notif-date" class="notif-date"></p>
        </div>
        <div class="custom-notif-modal-footer">
            <button class="btn btn-secondary" onclick="closeViewNotifModal()"><i class="fas fa-times-circle"></i> Close</button>
            <button class="btn btn-primary" onclick="backToNotifList()"><i class="fas fa-arrow-left"></i> Back</button>
        </div>
    </div>
    
    <!-- Modal Backdrop -->
    <div id="modalBackdrop" class="modal-backdrop"></div>
    
    <!-- JavaScript for modal toggling and actions -->
    <script>
        // Toggle notifications modal
        document.getElementById('notifModalButton').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('notifModal').classList.add('active');
            document.getElementById('modalBackdrop').classList.add('active');
        });
        function closeNotifModal() {
            document.getElementById('notifModal').classList.remove('active');
            document.getElementById('modalBackdrop').classList.remove('active');
        }
        
        // Select all checkboxes within the notifications form
        function selectAll() {
            const checkboxes = document.querySelectorAll('#notification-form input[type="checkbox"]');
            checkboxes.forEach(chk => chk.checked = true);
        }
        // Change the form action and submit the form via AJAX
        function submitForm(action, type) {
            const form = document.getElementById('notification-form');
            const formData = new FormData(form);
            fetch(action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (type === 'remove') {
                    document.querySelectorAll('#notification-form input[type="checkbox"]:checked').forEach(chk => {
                        chk.closest('.notif-item').remove();
                    });
                } else if (type === 'mark_read') {
                    document.querySelectorAll('#notification-form input[type="checkbox"]:checked').forEach(chk => {
                        chk.closest('.notif-item').classList.remove('unread');
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }
        
        // View full notification details in a separate modal
        function viewNotification(message, date) {
            const decodedMessage = decodeURIComponent(message)
                                                                .replace(/</g, "&lt;")
                                                                .replace(/>/g, "&gt;")
                                                                .replace(/"/g, "&quot;")
                                                                .replace(/'/g, "&#039;");
            document.getElementById('full-notif-message').innerHTML = decodedMessage;
            document.getElementById('notif-date').textContent = date;
            closeNotifModal();
            document.getElementById('viewNotifModal').classList.add('active');
            document.getElementById('modalBackdrop').classList.add('active');
        }
        function closeViewNotifModal() {
            document.getElementById('viewNotifModal').classList.remove('active');
            document.getElementById('modalBackdrop').classList.remove('active');
        }
        function backToNotifList() {
            document.getElementById('viewNotifModal').classList.remove('active');
            document.getElementById('notifModal').classList.add('active');
        }
    </script>
    

    <style>
        /* Modal container styles */
        .custom-notif-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1050;
        background-color: #fff;
        border-radius: 8px;
        width: 95%;
        max-width: 700px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        display: none;
        }
        .custom-notif-modal.active {
        display: block;
        }
        .custom-notif-modal-header,
        .custom-notif-modal-footer {
        padding: 20px;
        background-color: #f8f9fa;
        }
        .custom-notif-modal-header {
        border-bottom: 1px solid #dee2e6;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        }
        .custom-notif-modal-footer {
        border-top: 1px solid #dee2e6;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        text-align: right;
        }
        .custom-notif-modal-body {
        padding: 20px;
        max-height: 500px;
        overflow-y: auto;
        }
        .close-notif {
        cursor: pointer;
        font-size: 1.5rem;
        color: #dc3545;
        }
        /* Notification button row */
        .notif-button-row {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 12px 20px;
        background-color:rgba(248, 249, 250, 0.98);
        border-bottom: 1px solid rgba(222, 226, 230, 0.94);
        }
        .notif-button-row button {
        border: none;
        background: none;
        color: #333;
        font-size: 16px;
        cursor: pointer;
        font-weight: 400;
        }
        .notif-button-row button:hover {
        text-decoration: underline;
        }
        /* Notification item styling */
        .notif-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        border: 1px solid rgba(233, 236, 239, 0.97);
        }
        /* .notif-item:last-child {
        border-bottom: none;
        } */
        .notif-item.unread {
        background-color: whitesmoke;
        font-weight: bold;
        }
        .notif-item p {
        margin: 0;
        font-size: 16px;
        }
        .notif-date {
        font-size: 14px;
        color: #6c757d;
        }
        /* Add spacing to Font Awesome icons inside buttons */
        .notif-button-row button i {
        margin-right: 6px;
        }
        
        /* Modal Backdrop Styles  */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }
        .modal-backdrop.active {
            display: block;
        }
    </style>
</body>
</html>