<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="notif.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Admin Notifications</title>
</head>
<body class="notif-body">
    <?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        echo "<p>Access denied. Please log in as an admin.</p>";
        exit();
    }
    ?>
    <div class="notif-container">
        <h1>Admin Notifications</h1>
        <form id="send-notification-form" class="notif-form" action="send_notification.php" method="post" onsubmit="disableSubmitButton(event)">
            <label for="type">Notification Type:</label>
            <select name="type" id="type">
                <option value="success">Success</option>
                <option value="error">Error</option>
                <option value="info">Info</option>
                <option value="warning">Warning</option>
            </select>
            <br>
            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="4" cols="50"></textarea>
            <br>
            <label for="user-select">Select User:</label>
            <select name="user_id" id="user-select">
                <option value="all">All Users</option>
                <?php
                include '../includes/connection.php';
                // Fetch users from the database
                $sql = "SELECT user_id, username, full_name FROM user_table";
                $result = $con->query($sql);
                if ($result === false) {
                    error_log("Error executing query: " . $con->error);
                    die("Error executing query: " . $con->error);
                }
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['user_id']}' data-fullname='{$row['full_name']}' data-username='{$row['username']}'>Full Name: {$row['full_name']}, Username: {$row['username']}, User ID: {$row['user_id']}</option>";
                    }
                } else {
                    echo "<option value=''>No users found</option>";
                }
                ?>
            </select>
            <br>
            <button type="submit" id="submit-button"><i class="fas fa-paper-plane"></i> Send Notification</button>
        </form>
    </div>

    <div class="notif-container">
        <h2>Notifications</h2>
        <div class="notif-button-row">
            <button type="button" onclick="selectAll()"><i class="fas fa-check-square"></i> Select All</button>
            <button type="button" onclick="openModal('remove')"><i class="fas fa-trash-alt"></i> Remove Selected</button>
        </div>
        <div class="notif-list" style="max-height: 400px; overflow-y: auto;">
            <form id="notification-form" action="remove_notification.php" method="post">
                <?php
                $notifications = [];
                $current_time = time();
                $thirty_days = 30 * 24 * 60 * 60;

                include '../includes/connection.php';
                // Fetch notifications from the database
                $sql = "SELECT id, message, type, recipient, created_at FROM notifications WHERE created_at >= NOW() - INTERVAL 30 DAY ORDER BY created_at DESC";
                $result = $con->query($sql);
                if ($result === false) {
                    error_log("Error executing query: " . $con->error);
                    die("Error executing query: " . $con->error);
                }
                $all_users_messages = [];
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        if ($row['recipient'] == 'all') {
                            if (!in_array($row['message'], $all_users_messages)) {
                                $notifications[] = $row;
                                $all_users_messages[] = $row['message'];
                            }
                        } else {
                            $notifications[] = $row;
                        }
                    }
                }

                if (!empty($notifications)) {
                    foreach ($notifications as $notif) {
                        $recipient = $notif['recipient'] == 'all' ? 'All Users' : $notif['recipient'];
                        $sneak_peek = strlen($notif['message']) > 50 ? substr($notif['message'], 0, 50) . '...' : $notif['message'];
                        $created_at = date('Y-m-d H:i:s', strtotime($notif['created_at']));
                        echo "<div class='notif-item {$notif['type']}'>
                                <input type='checkbox' name='indexes[]' value='{$notif['id']}'>
                                <p><a href='#' onclick='viewNotification(\"" . addslashes($notif['message']) . "\", \"{$created_at}\")'>$sneak_peek</a></p>
                                <span class='notif-date'>$created_at</span>
                              </div>";
                    }
                } else {
                    echo "<p>No notifications available.</p>";
                }
                ?>
            </form>
        </div>
    </div>

    <!-- View Notification Modal -->
    <div id="view-notification-modal" class="notif-modal">
        <div class="notif-modal-content">
            <span class="notif-close" onclick="closeViewNotificationModal()">&times;</span>
            <h2>Notification Details</h2>
            <p id="full-notification-message" style="word-wrap: break-word; text-align: left;"></p>
            <p id="notification-date" style="text-align: left; color: gray;"></p>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="notif-modal">
        <div class="notif-modal-content">
            <span class="notif-close" onclick="closeModal()">&times;</span>
            <p id="modal-message"></p>
            <button type="button" onclick="submitForm()">Confirm</button>
        </div>
    </div>

    <script>
        function disableSubmitButton(event) {
            event.preventDefault();
            document.getElementById('submit-button').disabled = true;
            const form = document.getElementById('send-notification-form');
            const formData = new FormData(form);
            fetch('send_notification.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                showModal(data);
                document.getElementById('submit-button').disabled = false;
            })
            .catch(error => {
                showModal('Error sending notification: ' + error);
                document.getElementById('submit-button').disabled = false;
            });
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => checkbox.checked = true);
        }

        function openModal(action) {
            const modal = document.getElementById('modal');
            const modalMessage = document.getElementById('modal-message');
            modalMessage.textContent = 'Are you sure you want to remove the selected notifications?';
            modal.style.display = 'block';
            document.getElementById('notification-form').action = 'remove_notification.php';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function showModal(message) {
            const modal = document.getElementById('modal');
            const modalMessage = document.getElementById('modal-message');
            modalMessage.textContent = message;
            modal.style.display = 'block';
        }

        function submitForm() {
            document.getElementById('notification-form').submit();
        }

        function viewNotification(message, date) {
            document.getElementById('full-notification-message').textContent = message;
            document.getElementById('notification-date').textContent = date;
            document.getElementById('view-notification-modal').style.display = 'block';
        }

        function closeViewNotificationModal() {
            document.getElementById('view-notification-modal').style.display = 'none';
        }
    </script>
</body>
</html>
