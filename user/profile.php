<?php
session_start();
include __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    echo "User ID not set in session.";
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM user_table WHERE user_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
} else {
    echo "Error executing query: " . mysqli_error($con);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>RS WOODWORKS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/notif.css">
    <link rel="stylesheet" href="../css/chatroom.css">
    <script src="https://js.stripe.com/v3/"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="../css/userprofile.css">
</head>
<body>
   <!-- Navbar -->
   <nav class="navbar navbar-expand-lg shadow-sm py-3">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="../home.php">
            <img src="../media/rswoodlogo.png" alt="RS WOODWORKS Logo" class="logo-img" style="width: 100%; height: 70px;">
        </a>
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style=" font-size: 20px; color: #00094b;"><i class="fa-solid fa-bars"></i></span>
        </button>
        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <!-- Home, Products, About, Contact Links -->
                <li class="nav-item"><a class="nav-link" href="../home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../product.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="../home.php#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="../home.php#contact-us">Contact</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFurniture" role="button" data-bs-toggle="dropdown" aria-expanded="false">Furniture Configurator</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownFurniture">
                        <!-- Dropdown links -->
                        <li><a class="dropdown-item" href="../2dconfig.php">Dining Table</a></li>
                        <li><a class="dropdown-item" href="../benchconfig.php">Bench</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto d-flex">
                <!-- Search Bar -->
                <li class="nav-item mx-2 position-relative" style="max-width: 400px;">
                    <form class="d-flex" method="GET">
                        <input class="form-control" type="search" placeholder="Search for products" aria-label="Search" name="search" id="searchInput" onkeyup="searchProducts()" style="height: 50px; font-size: 1.2rem; padding: 10px 15px; width: 100%; border-radius: 25px;">
                    </form>
                    <!-- Floating search results container -->
                    <div id="searchResults" class="dropdown-menu w-100" style="position: absolute; z-index: 10;"></div>
                </li>
                <script>
                    function searchProducts() {
                    let searchQuery = document.getElementById('searchInput').value;

                    // Show results only if the search query is not empty
                    if (searchQuery.length > 0) {
                        let xhr = new XMLHttpRequest();
                        xhr.open('GET', 'search.php?query=' + searchQuery, true);
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                let results = xhr.responseText;
                                let resultsContainer = document.getElementById('searchResults');

                                // Display the dropdown results
                                if (results.trim() === '') {
                                    resultsContainer.innerHTML = '<a class="dropdown-item" href="#">No products found</a>';
                                } else {
                                    resultsContainer.innerHTML = results;
                                }
                            }
                        };
                        xhr.send();
                    } else {
                        document.getElementById('searchResults').innerHTML = '';
                    }
                }
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
                    <div class="modal chat-modal" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-body" id="chatModalBody">
                                    <!-- Chatroom content will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.getElementById('chatModalButton').addEventListener('click', function() {
                            let xhr = new XMLHttpRequest();
                            xhr.open('GET', '../chatroom/user_chatroom.php', true);
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
                            fetch("../chatroom/fetch_message.php", {
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
                        fetch("../chatroom/send_message.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: `message=${encodeURIComponent(message)}&receiver_id=${other_id}&sender_type=user`
                        });
                        }

                        function sendFAQ(faqQuestion) {
                        // Display the user's question immediately in the chat
                        displayMessage(faqQuestion, true);

                        // Send the question to the server to appear in the history as a user message
                        fetch("../chatroom/send_message.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: `message=${encodeURIComponent(faqQuestion)}&receiver_id=${other_id}&sender_type=user`
                        })
                        .then(() => {
                            // Get the automated response
                            const faqResponse = faqResponses[faqQuestion];

                            // Send the automated response to the server as an admin message
                            return fetch("../chatroom/send_message.php", {
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
                    <div id="notifModal" class="custom-modal" style="display: none;">
                        <div class="custom-modal-content">
                            <button id="closeNotifModal" style="position: absolute; top: 10px; right: 10px;">&times;</button>
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
                            fetch("../notification/user_notif.php")
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
                                <div class="custom-modal" style="display: block;">
                                    <div class="custom-modal-content">
                                        <div id="notif-container">
                                            <div id="notif-box" style="max-height: 400px; overflow-y: auto;">
                                                <h2>Notification Details</h2>
                                                <p>${encodedMessage}</p>
                                                <p>${date}</p>
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
                            fetch('../notification/mark_read_notifications.php', {
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
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="./logout.php">Logout</a></li>
                        </ul>
                    </li>
                    <!-- Cart Icon -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="../cart.php">
                            <i class="bi bi-bag-check-fill"></i>
                            <span class="badge bg-danger"><?php cart_item(); ?></span>
                        </a>
                    </li>
                    <?php else: ?>
                        <!-- Login Button -->
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-3 py-1" href="./login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </ul>
        </div>
    </div>
</nav>
    <div class="row justify-content-center" style="margin-top: 10px;">
        <div class="col-md-4">
            <div class="profile-card shadow-lg">
                <div class="profile-header position-relative">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#updateImageModal">
                        <img src="<?php echo $user['user_image']; ?>" class="profile-img" alt="Profile Image">
                        <div class="edit-icon">
                            <i class="bi bi-pencil"></i>
                        </div>
                    </a>
                </div>
                <div class="profile-body">
                    <h3 class="profile-name"><?php echo $user['full_name']; ?></h3>
                    <p class="profile-username">@<?php echo $user['username']; ?></p>
                    <ul class="profile-info">
                        <li>
                            <i class="bi bi-envelope"></i> 
                            <span style="font-weight: bold;">Email:</span>
                            <p><?php echo $user['user_email']; ?></p>
                        </li>
                        <li>
                            <i class="bi bi-telephone"></i> 
                            <span style="font-weight: bold;">Contact: </span>
                            <p><?php echo $user['user_contact']; ?></p>
                        </li>
                        <li>
                            <i class="bi bi-geo-alt"></i>
                            <span style="font-weight: bold;">Address:</span> 
                            <p><?php echo $user['user_address']; ?></p>
                        </li>
                    </ul>
                    <div class="profile-actions">
                        <button style="background: none; color: #333; border: none; font-size: 15px; border-radius: none;" type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Information
                        </button>
                        <button style="background: none; color: #333; border: none; font-size: 15px; border-radius: none;" type="button" class="btn" data-bs-toggle="modal" data-bs-target="#changePass">
                            <i class="fa-solid fa-pen-to-square"></i> Change Password
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Updating Profile Image -->
        <div class="modal fade" id="updateImageModal" tabindex="-1" aria-labelledby="updateImageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateImageModalLabel">Update Profile Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="update_image.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="newImage" class="form-label">Select New Profile Image</label>
                                <input type="file" class="form-control" id="newImage" name="user_image" accept="image/*" required>
                            </div>
                            <p class="text-center">Are you sure you want to update your profile image?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for success message -->
        <div id="passwordSuccessModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Password changed successfully!</p>
            </div>
        </div>

        <script>
            var modal = document.getElementById('passwordSuccessModal');
            var closeBtn = modal.querySelector('.close');
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        </script>

        <?php
        if (isset($_GET['password_changed']) && $_GET['password_changed'] == 1) {
            echo '<script>document.getElementById("passwordSuccessModal").style.display = "block";</script>';
        }
        ?>

        <div class="modal fade" id="changePass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"><h2>Change Password</h2></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form style="align-items: center;" action="change_password.php" method="post">
                            <label for="current_password">Current Password:</label><br>
                            <input type="password" id="current_password" name="current_password" required><br><br>
                            <label for="new_password">New Password:</label><br>
                            <input type="password" id="new_password" name="new_password" required><br><br>
                            <label for="confirm_password">Confirm New Password:</label><br>
                            <input type="password" id="confirm_password" name="confirm_password" required><br><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input class="btn btn-danger" type="submit" value="Change Password">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Your changes have been saved successfully.
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Information Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: lightgray;">
                        <h5 class="modal-title" id="editModalLabel">Edit User Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateForm" action="./update_profile.php" method="POST">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo isset($user['full_name']) ? $user['full_name'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($user['user_email']) ? $user['user_email'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact" class="form-label">Contact</label>
                                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo isset($user['user_contact']) ? $user['user_contact'] : ''; ?>" required>
                            </div>
                            <h5>User Address</h5>
                            <div class="mb-3">
                                <label for="region" class="form-label">Region</label>
                                <input type="text" class="form-control" id="region" name="region" value="<?php echo isset($user['region']) ? $user['region'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City/Barangay/District</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo isset($user['city']) ? $user['city'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="street_building" class="form-label">Street/Bldg.</label>
                                <input type="text" class="form-control" id="street_building" name="street_building" value="<?php echo isset($user['street_building']) ? $user['street_building'] : ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="unit_floor" class="form-label">Unit/Floor</label>
                                <input type="text" class="form-control" id="unit_floor" name="unit_floor" value="<?php echo isset($user['unit_floor']) ? $user['unit_floor'] : ''; ?>" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="confirmSaveChanges">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirm Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Changes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to save these changes?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="proceedSaveChanges">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#confirmSaveChanges').click(function() {
                    $('#confirmModal').modal('show');
                    $('#editModal').modal('hide');
                });

                $('#proceedSaveChanges').click(function() {
                    $('#updateForm').submit();
                    $('#confirmModal').modal('hide');
                });

                $('#updateForm').submit(function(event) {
                    event.preventDefault();
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $('#successModal').modal('show');
                            $('#editModal').modal('hide');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>

        <!--orders table -->
        <div class="col-md-8">
            <section id="orders-section">
                <div class="card">
                    <div class="button-list" style="margin-top: 10px; margin-left: 10px; margin-bottom: 10px;">
                        <button id="toggleOrdersBtn" class="btn" style="font-size: 17px; font-weight:500;">Orders</button>
                        <button id="toggleShippedOrdersBtn" class="btn" style="font-size: 17px; font-weight:500;">To Receive</button>
                        <button id="toggleCompletedOrdersBtn" class="btn" style="font-size: 17px; font-weight:500;">Completed</button>
                        <script>
                            const ordersBtn = document.getElementById('toggleOrdersBtn');
                            const shippedOrdersBtn = document.getElementById('toggleShippedOrdersBtn');
                            const completedOrdersBtn = document.getElementById('toggleCompletedOrdersBtn');

                            ordersBtn.addEventListener('click', () => {
                                toggleActive(ordersBtn);
                            });

                            shippedOrdersBtn.addEventListener('click', () => {
                                toggleActive(shippedOrdersBtn);
                            });

                            completedOrdersBtn.addEventListener('click', () => {
                                toggleActive(completedOrdersBtn);
                            });

                            $(document).ready(function() {
                                $('#toggleOrdersBtn, #toggleShippedOrdersBtn, #toggleCompletedOrdersBtn').click(function() {
                                    $('.btn').removeClass('active');
                                    $(this).addClass('active');
                                });
                            });

                            function toggleActive(button) {
                                const buttons = document.querySelectorAll('.btn');
                                buttons.forEach(btn => {
                                    btn.classList.remove('active');
                                });
                                button.classList.add('active');
                            }
                        </script>
                    </div>

                    <!-- Order Items -->
                    <div id="ordersTable">
                        <?php
                        $sql = "SELECT o.*, p.product_description FROM orders o
                                JOIN products p ON o.product_id = p.product_id
                                WHERE o.user_id = ?";
                        $stmt = mysqli_prepare($con, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (!$result || mysqli_num_rows($result) === 0) {
                            echo "
                            <div class='empty-image' style='text-align: center;'>
                                <img src='../media/empty.svg'>
                            </div>
                            <p style='text-align: center; font-size: 25px; color: #333; font-weight: 500; margin-bottom: 100px;'>No Pending Orders Found.</p>";
                        } else {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $totalPrice = $row['product_price'] * $row['quantity'];
                                ?>
                                <div class="order-item" id="order-<?php echo $row['order_id']; ?>">
                                    <img src="../admin/product_images/<?php echo $row['product_image']; ?>" alt="Product Image" class="order-image">
                                    <div class="order-details">
                                        <h5 class="order-name"><?php echo $row['product_name']; ?></h5>
                                        <p class="order-price">Price: ₱<?php echo $row['product_price']; ?></p>
                                        <p class="order-quantity" style="font-weight: 800; color: #333;">Qty: <?php echo $row['quantity']; ?></p>
                                        <p class="order-description" style="font-style: oblique; font-weight: 400; font-size: 13px; color: gray;">Description: <?php echo $row['product_description']; ?></p>
                                        <p class="order-total" style="font-weight: 700;">Total Price (<?php echo $row['quantity']; ?>): ₱<?php echo $totalPrice; ?></p>
                                        <?php if (isset($row['payment_method']) && $row['payment_method'] === 'PayMongo'): ?>
                                        <p class="order-payment-method" style="font-size: 13px; font-weight: 400; color: lightgreen;">Payment Method: Online Payment</p>
                                        <?php elseif (isset($row['payment_method']) && $row['payment_method'] === 'Cash On Delivery'): ?>
                                        <p class="order-payment-method" style="font-size: 13px; font-weight: 400; color: blue;">Method of Payment: Cash on Delivery</p>
                                        <button class="cancel-btn btn btn-danger" data-order-id="<?php echo $row['order_id']; ?>" data-toggle="modal" data-target="#cancelOrderConfirmModal">Cancel Order</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>

                    <!-- Cancel Order Confirmation Modal (Bootstrap) -->
                    <div class="modal fade" id="cancelOrderConfirmModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderConfirmModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cancelOrderConfirmModalLabel">Cancel Order</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to cancel this order?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-danger" id="confirmCancelOrderBtn">Yes, Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cancel Order Success Modal (Bootstrap) -->
                    <div class="modal fade" id="cancelOrderSuccessModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderSuccessModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cancelOrderSuccessModalLabel">Order Cancelled</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Order cancelled successfully.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="closeCancelOrderSuccessModal">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            let orderIdToCancel = null;

                            document.querySelectorAll('.cancel-btn').forEach(button => {
                                button.addEventListener('click', function () {
                                    orderIdToCancel = this.getAttribute('data-order-id');
                                    $('#cancelOrderConfirmModal').modal('show');
                                });
                            });

                            document.getElementById('confirmCancelOrderBtn').addEventListener('click', function () {
                                if (orderIdToCancel) {
                                    fetch('cancel_order.php', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ order_id: orderIdToCancel })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            document.getElementById(`order-${orderIdToCancel}`).remove();
                                            $('#cancelOrderSuccessModal').modal('show');
                                            $('#cancelOrderConfirmModal').modal('hide');
                                            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                                        } else {
                                            alert('Failed to cancel order. Please try again.');
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                                }
                            });
                        });
                    </script>

                    <!-- Bootstrap Modal for Confirmation -->
                    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to confirm that you received the order?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="confirmOrderReceived">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Bootstrap Modal for Success Message -->
                    <div class="modal fade" id="orderReceivedSuccess" tabindex="-1" aria-labelledby="orderReceivedSuccessLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="orderReceivedSuccessLabel">Order Received!</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                    <p class="mt-3">Your order has been received. Thank you for your purchase!</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            const urlParams = new URLSearchParams(window.location.search);
                            const orderReceivedSuccess = urlParams.get('order_received_success');

                            if (orderReceivedSuccess === 'true') {
                                $('#orderReceivedSuccess').modal('show');
                            }
                        });
                    </script>

                    <!-- shipped orders table -->
                    <div id="shippedOrdersTable" style="display: none;">
                        <?php
                        $sql = "SELECT so.*, p.product_description, so.delivery_date FROM shipped_orders so
                                JOIN products p ON so.product_id = p.product_id
                                WHERE so.user_id = ?";
                        $stmt = mysqli_prepare($con, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        if (!$result || mysqli_num_rows($result) === 0) {
                            echo "
                            <div class='empty-image' style='text-align: center;'>
                                <img src='../media/empty.svg'>
                            </div>
                            <p style='text-align: center; font-size: 25px; color: #333; font-weight: 500; margin-bottom: 100px;'>No Shipped Orders Found.</p>";
                        } else {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $totalPrice = $row['quantity'] * $row['product_price'];
                                $currentDate = new DateTime("now", new DateTimeZone('Asia/Manila'));
                                $deliveryDate = new DateTime($row['delivery_date'], new DateTimeZone('Asia/Manila'));
                                ?>
                                <div class="order-date">
                                    <p><i class="bi bi-truck"></i> This item will be delivered on <span><?php echo $deliveryDate->format('Y-m-d'); ?></span>. Please be ready.</p>
                                </div>
                                <div class="order-item">
                                    <img src="../admin/product_images/<?php echo $row['product_image']; ?>" alt="Product Image" class="order-image">
                                    <div class="order-details">
                                        <h5 class="order-name"><?php echo $row['product_name']; ?></h5>
                                        <p class="order-price">Price: ₱<?php echo $row['product_price']; ?></p>
                                        <p class="order-quantity" style="font-weight: 800; color: #333;">Qty: <?php echo $row['quantity']; ?></p>
                                        <p class="order-description" style="font-style: oblique; font-weight: 400; font-size: 13px; color: gray;">Description: <?php echo $row['product_description']; ?></p>
                                        <p class="order-total" style="font-weight: 700;">Total Price (<?php echo $row['quantity']; ?>): ₱<?php echo $totalPrice; ?></p>
                                        <?php if ($row['payment_method'] === 'PayMongo'): ?>
                                            <p class="order-payment-method" style="font-size: 13px; font-weight: 400; color: lightgreen;">Payment Method: Online Payment</p>
                                        <?php elseif ($row['payment_method'] === 'Cash On Delivery'): ?>
                                            <p class="order-payment-method" style="font-size: 13px; font-weight: 400; color: blue;">Method of Payment: Cash on Delivery</p>
                                        <?php endif; ?>
                                        
                                        <div class="order-action">
                                            <?php if ($currentDate->format('Y-m-d') === $deliveryDate->format('Y-m-d')): ?>
                                                <form class="order-form" action="order_received.php" method="post" data-order-id="<?php echo $row['product_id']; ?>">
                                                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                                    <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
                                                    <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>">
                                                    <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>">
                                                    <input type="hidden" name="quantity" value="<?php echo $row['quantity']; ?>">
                                                    <input type="hidden" name="user_address" value="<?php echo $user_address; ?>">
                                                    <input type="hidden" name="order_received" value="1">
                                                    <button type="button" class="btn btn-primary order-received-btn" data-bs-toggle="modal" data-bs-target="#confirmationModal">Order Received</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>

                    <!-- Completed Orders Table -->
                    <div id="completedOrdersTable" style="display: none;">
                        <?php
                        $sql = "SELECT co.*, p.product_description FROM completed_order co
                                JOIN products p ON co.product_id = p.product_id
                                WHERE co.user_id = ?";
                        $stmt = mysqli_prepare($con, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        if (!$result || mysqli_num_rows($result) === 0) {
                            echo "
                            <div class='empty-image' style='text-align: center;'>
                                <img src='../media/empty.svg'>
                            </div>
                            <p style='text-align: center; font-size: 25px; color: #333; font-weight: 500; margin-bottom: 100px;'>No Completed Orders Found.</p>";
                        } else {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $totalPrice = $row['quantity'] * $row['product_price'];
                                ?>
                                <div class="order-date">
                                    <p><i class="bi bi-patch-check-fill"></i> This item has been confirmed to be received successfully on <span><?php echo $row['date_received']; ?></span></p>
                                </div>
                                <div class="order-item">
                                    <img src="../admin/product_images/<?php echo $row['product_image']; ?>" alt="Product Image" class="order-image">
                                    <div class="order-details">
                                        <h5 class="order-name"><?php echo $row['product_name']; ?></h5>
                                        <p class="order-price">Price: ₱<?php echo $row['product_price']; ?></p>
                                        <p class="order-quantity" style="font-weight: 800; color: #333;">Qty: <?php echo $row['quantity']; ?></p>
                                        <p class="order-description" style="font-style: oblique; font-weight: 400; font-size: 13px; color: gray;">Description: <?php echo $row['product_description']; ?></p>
                                        <p class="order-total" style="font-weight: 700;">Total Price (<?php echo $row['quantity']; ?>): ₱<?php echo $totalPrice; ?></p>
                                        <a href="../product_details.php?product_id=<?php echo $row['product_id']; ?>#reviewSection"> <button type="button" class="btn btn-primary order-received-btn" data-bs-toggle="modal" data-bs-target="#confirmationModal">Review</button></a>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#confirmOrderReceived').click(function() {
                            $('#confirmationModal').modal('hide');
                            var orderId = $(this).data('order-id');
                            console.log('Order ID:', orderId);
                            $('form[data-order-id="' + orderId + '"]').submit();
                        });

                        $('.order-received-btn').click(function() {
                            var orderId = $(this).closest('form').data('order-id');
                            $('#confirmOrderReceived').data('order-id', orderId);
                        });
                    });
                </script>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#shippedOrdersTable, #completedOrdersTable').hide();

                        if ($('#ordersTable').children().length > 0) {
                            $('#ordersTable').show();
                        } else {
                            $('#ordersTable').html(`
                                <div class='empty-image' style='text-align: center;'>
                                    <img src='../media/empty.svg' alt='No Pending Orders' style='width: 100px; height: 100px;'>
                                </div>
                                <p style='text-align: center; font-size: 25px; color: #333; font-weight: 500; margin-bottom: 100px;'>No Pending Orders Found.</p>
                            `).show();
                        }

                        $('#toggleOrdersBtn').click(function() {
                            $('#shippedOrdersTable, #completedOrdersTable').slideUp();
                            $('#ordersTable').slideToggle();
                            return false;
                        });

                        $('#toggleShippedOrdersBtn').click(function() {
                            $('#shippedOrdersTable').slideToggle();
                            $('#completedOrdersTable, #ordersTable').slideUp();
                            return false;
                        });

                        $('#toggleCompletedOrdersBtn').click(function() {
                            $('#ordersTable, #shippedOrdersTable').hide();
                            $('#completedOrdersTable').slideToggle();
                            return false;
                        });
                    });
                </script>
            </section>
        </div>
    </div>

    <footer style="margin-top: 50px;" class="section-p1">
        <div class="col">
            <img src="../media/bluerslogo.png" class="logo" width="300px" height="100px">
            <h4>Contact</h4>
            <p><strong>Address:</strong></p>
            <p><strong>Contact:</strong> 09203424552</p>
            <p><strong>Email:</strong> rswoodworks@gmail.com</p>
        </div>

        <div class="col">
            <h4>About</h4>
            <a href="../home.php#about">About Us</a>
            <a href="">Privacy Policy</a>
            <a href="">Terms & Conditions</a>
            <a href="../home.php#contact">Contact Us</a>
        </div>
        <div class="col">
            <h4>My Account</h4>
            <a href="user/login.php">Sign In</a>
            <a href="../cart.php">View Cart</a>
            <a href="user/profile.php">My Orders</a>
            <a href="../home.php#contact">Help</a>
        </div>

        <div class="install">
            <div class="follow" style="margin-bottom: 20px;">
                <h4>Follow Us</h4>
                <div class="icon">
                    <i class="fa fa-facebook" style="font-size: 30px; margin-right:4px;"></i>
                    <i class="fa fa-twitter" style="font-size: 30px; margin-right:4px;"></i>
                    <i class="fa fa-instagram" style="font-size: 30px; margin-right:4px;"></i>
                </div>
            </div>

            <h4>Secured Payment Gateways</h4>
            <style>
                .install {
                    justify-content: space-between;
                    margin-right: 30px;
                }
                .install img {
                    background: whitesmoke;
                    padding: 2px;
                    border-radius: 5px;
                    width: 100px;
                    height: 50px;
                }
            </style>
            <img src="../media/paymaya.png" width="100%" height="35px">
            <img src="../media/gcash.png" width="100%" height="35px">
        </div>
        <div class="copyright">
            © 2024 RS WoodWorks. All rights reserved.
        </div>
    </footer>
</body>
</html>

