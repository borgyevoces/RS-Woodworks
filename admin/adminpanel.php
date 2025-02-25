<?php
include('../includes/connection.php'); 

session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: ../user/login.php");
    exit();
}

// Fetch counts from the database
$user_count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS user_count FROM user_table"))['user_count'] ?? 0;
$product_count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS product_count FROM products"))['product_count'] ?? 0;
$total_orders = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS total_orders FROM orders"))['total_orders'] ?? 0;
$completedOrdersCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS completed_orders_count FROM completed_order"))['completed_orders_count'] ?? 0;
$totalRevenue = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(product_price) AS total_revenue FROM completed_order"))['total_revenue'] ?? 0;
$formattedTotalRevenue = '₱' . number_format($totalRevenue, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <!-- CSS & JS Libraries -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <link rel="stylesheet" href="../css/adminpanel.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Inline CSS to handle section visibility -->
  <style>
    .section { 
      display: none; 
    }
    .section.active { 
      display: block; 
    }
  </style>
</head>
<body>
  <!-- Mobile Header with Toggle Button -->
  <div class="mobile-header">
    <div class="logo">Admin Panel</div>
    <button id="mobile-toggle-btn" class="mobile-toggle-btn">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="logo">Admin Panel</div>
    <!-- Optional Close Button (for mobile view) -->
    <button id="toggle-btn" class="toggle-btn">
      <i class="fas fa-times"></i>
    </button>
    <ul class="side-menu">
      <li>
        <a href="#" onclick="showSection('dashboard')" id="link-dashboard">
          <i class="fas fa-tachometer-alt"></i><span>&nbsp;Dashboard</span>
        </a>
      </li>
      <li>
        <a href="#" onclick="showSection('notif')" id="link-notif">
          <i class="fas fa-bell"></i><span>&nbsp;Notifications</span>
        </a>
      </li>
      <li>
        <a href="#" onclick="showSection('orders')" id="link-orders">
          <i class="fas fa-shopping-cart"></i><span>&nbsp;Orders</span>
        </a>
      </li>
      <li>
        <a href="#" onclick="showSection('products')" id="link-products">
          <i class="fas fa-box"></i><span>&nbsp;Products</span>
        </a>
      </li>
      <li>
        <a href="#" onclick="showSection('messages')" id="link-messages">
          <i class="fas fa-envelope"></i><span>&nbsp;Messages</span>
        </a>
      </li>
      <li>
        <a href="#" onclick="showSection('users')" id="link-users">
          <i class="fas fa-users"></i><span>&nbsp;Users</span>
        </a>
      </li>
      <li>
        <a href="#" onclick="showSection('admin')" id="link-admin">
          <i class="fas fa-user-shield"></i><span>&nbsp;Admin Management</span>
        </a>
      </li>
      <li>
        <a href="../product.php">
          <i class="fas fa-store"></i><span>&nbsp;Store</span>
        </a>
      </li>
      <!-- Spacer to push logout to bottom -->
      <li class="spacer"></li>
      <li class="logout">
        <a href="../user/logout.php">
          <i class="fas fa-sign-out-alt"></i><span>&nbsp;Logout</span>
        </a>
      </li>
    </ul>
  </div>

  <!-- Main Content Sections -->
  <div class="content">
    <!-- Dashboard Section -->
    <div id="dashboard" class="section">
      <div class="header">
        <h1 style="font-weight: bold; font-size: 30px;">Dashboard</h1>
      </div>
       <!-- Insights -->
       <div class="insights row">
        <?php
        $insights = [
            ['icon' => 'fas fa-shopping-cart', 'value' => $total_orders, 'label' => 'Total Orders', 'bg' => 'transparent-green-bg'],
            ['icon' => 'fas fa-users', 'value' => $user_count, 'label' => 'Users', 'bg' => 'transparent-blue-bg'],
            ['icon' => 'fas fa-box', 'value' => $product_count, 'label' => 'Products', 'bg' => 'transparent-yellow-bg'],
            ['icon' => 'fas fa-check-circle', 'value' => $completedOrdersCount, 'label' => 'Completed Orders', 'bg' => 'transparent-cyan-bg'],
            ['icon' => 'fa-solid fa-chart-line', 'value' => $formattedTotalRevenue, 'label' => 'Total Revenue', 'bg' => 'transparent-red-bg'],
        ];

        foreach ($insights as $insight) {
            echo "<div class='col-lg-2 col-md-3 col-sm-4 col-6 insight-item {$insight['bg']} text-white'>
                    <i class='{$insight['icon']}'></i>
                    <span class='info'>
                        <h3>{$insight['value']}</h3>
                        <p>{$insight['label']}</p>
                    </span>
                </div>";
        }
        ?>
      </div>
      <!-- Charts -->
      <div class="chart-container">
        <?php
        // Define queries and chart labels
        $charts = [
        ['label' => 'New User Registrations', 'query' => "SELECT YEAR(date_created) AS year, WEEK(date_created) AS week, COUNT(*) AS value FROM user_table GROUP BY year, week ORDER BY year, week", 'canvasId' => 'registrationsChart', 'type' => 'bar', 'backgroundColor' => ['rgba(153, 102, 255, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(255, 159, 64, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)'], 'borderColor' => ['rgba(153, 102, 255, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 159, 64, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'], 'size' => 'large'],
        ['label' => 'Monthly Revenue', 'query' => "SELECT DATE_FORMAT(date_received, '%Y-%m') AS label, SUM(product_price) AS value FROM completed_order GROUP BY label ORDER BY label", 'canvasId' => 'revenueChart', 'type' => 'line', 'backgroundColor' => 'rgba(75, 192, 192, 0.2)', 'borderColor' => 'rgba(75, 192, 192, 1)', 'size' => 'medium'],  
        ['label' => 'Total Orders', 'query' => "SELECT DATE(date) AS label, COUNT(*) AS value FROM orders GROUP BY label ORDER BY label", 'canvasId' => 'orderPieChart', 'type' => 'pie', 'backgroundColor' => ['rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)'], 'borderColor' => ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'], 'size' => 'small'],
        ['label' => 'Weekly User Activity', 'query' => "SELECT YEAR(last_login) AS year, WEEK(last_login) AS week, COUNT(*) AS value FROM user_table GROUP BY year, week ORDER BY year, week", 'canvasId' => 'userActivityChart', 'type' => 'line', 'backgroundColor' => 'rgba(255, 159, 64, 0.2)', 'borderColor' => 'rgba(255, 159, 64, 1)', 'size' => 'large'],
        ['label' => 'Top Rated Products', 'query' => "SELECT p.product_name AS label, AVG(r.rating) AS value FROM reviews r JOIN products p ON r.product_id = p.product_id GROUP BY p.product_name ORDER BY value DESC LIMIT 5", 'canvasId' => 'topRatedProductsChart', 'type' => 'bar', 'backgroundColor' => 'rgba(54, 162, 235, 0.6)', 'borderColor' => 'rgba(54, 162, 235, 1)', 'size' => 'medium']
        ];

        $chartData = [];
        foreach ($charts as $chart) {
        $result = mysqli_query($con, $chart['query']);
        $labels = $values = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $labels[] = isset($row['week']) ? 'Week ' . $row['week'] . ', ' . $row['year'] : (isset($row['label']) ? $row['label'] : '');
            $values[] = $row['value'];
        }
        $chartData[] = json_encode(['labels' => $labels, 'values' => $values]);
        }
        ?>
        <?php foreach ($charts as $index => $chart): ?>
          <div class="chart <?php echo strtolower(str_replace(' ', '-', $chart['label'])); ?>-chart <?php echo $chart['size']; ?>">
        <h2><?php echo $chart['label']; ?></h2>
        <canvas id="<?php echo $chart['canvasId']; ?>"></canvas>
          </div>
        <?php endforeach; ?>
      </div>

      <script>
        var chartData = <?php echo json_encode($chartData); ?>;
        var chartConfig = [
          { ctxId: 'registrationsChart', type: 'bar', bgColor: ['rgba(153, 102, 255, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(255, 159, 64, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)'], borderColor: ['rgba(153, 102, 255, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 159, 64, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'] },
          { ctxId: 'revenueChart', type: 'line', bgColor: 'rgba(75, 192, 192, 0.2)', borderColor: 'rgba(75, 192, 192, 1)' },
          { ctxId: 'orderPieChart', type: 'pie', bgColor: ['rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)'], borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'] },
          { ctxId: 'userActivityChart', type: 'line', bgColor: 'rgba(255, 159, 64, 0.2)', borderColor: 'rgba(255, 159, 64, 1)' },
          { ctxId: 'topRatedProductsChart', type: 'bar', bgColor: 'rgba(54, 162, 235, 0.6)', borderColor: 'rgba(54, 162, 235, 1)' }
        ];
        chartData.forEach(function(data, i) {
          var ctx = document.getElementById(chartConfig[i].ctxId).getContext('2d');
          var config = chartConfig[i];
          new Chart(ctx, {
        type: config.type,
        data: {
          labels: JSON.parse(data).labels,
          datasets: [{
            label: config.ctxId.replace('Chart', '').replace(/([A-Z])/g, ' $1').trim(),
            data: JSON.parse(data).values,
            backgroundColor: config.bgColor,
            borderColor: config.borderColor,
            borderWidth: 1,
            fill: config.type === 'line'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          scales: { y: { beginAtZero: true } }
        }
          });
        });
      </script>
    </div>


    <!-- Notifications Section -->
    <div id="notif" class="section">
      <div class="header">
      <h1 style="font-weight: bold; font-size: 24px;">Notification Management</h1>
      </div>
      <div class="row g-4">
      <!-- Send Notification Form -->
      <div class="col-lg-5 col-md-6 ">
      <div class="card shadow-sm border-0" style="height: 100%;">
      <div class="card-header bg-primary text-white rounded-top py-3">
        <h5 class="mb-0 fs-4">Send Notification</h5>
      </div>
      <div class="card-body p-3">
        <form id="send-notification-form" onsubmit="disableSubmitButton(event)">
        <div class="mb-3">
        <label for="type-select" class="form-label fs-6">Notification Type</label>
        <select class="form-select form-control-sm" name="type" id="type-select" required>
        <option value="info">Info</option>
        <option value="warning">Warning</option>
        <option value="success">Success</option>
        </select>
        </div>
        <div class="mb-3">
        <label for="message" class="form-label fs-6">Message</label>
        <textarea class="form-control form-control-sm" name="message" id="message" rows="10" placeholder="Enter your notification message here..." required></textarea>
        </div>
        <div class="mb-3">
        <label for="user-select" class="form-label fs-6">Select User</label>
        <select class="form-select form-control-sm" name="user_id" id="user-select" required>
        <option value="all">All Users</option>
        <?php
          include '../includes/connection.php';
          $sql = "SELECT user_id, username, full_name FROM user_table";
          $result = $con->query($sql);
          if ($result !== false && $result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
          echo "<option value='{$row['user_id']}' data-fullname='{$row['full_name']}' data-username='{$row['username']}'>";
          echo "{$row['full_name']} (@{$row['username']})";
          echo "</option>";
          }
          } else {
          echo "<option value=''>No users found</option>";
          }
        ?>
        </select>
        </div>
        <button type="submit" id="submit-button" class="btn btn-primary btn-sm w-100">
        <i class="fas fa-paper-plane"></i> Send Notification
        </button>
        </form>
      </div>
      </div>
      </div>

      <!-- Notifications List -->
      <div class="col-lg-7 col-md-6">
        <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-secondary text-white py-3">
          <h5 class="mb-0 fs-4">Notifications</h5>
          <div>
          <button type="button" class="btn btn-outline-light btn-sm me-2" onclick="selectAll()">
            <i class="fas fa-check-square"></i> Select All
          </button>
          <button type="button" class="btn btn-danger btn-sm" onclick="openModal('remove')">
            <i class="fas fa-trash-alt"></i> Remove Selected
          </button>
          </div>
        </div>
        <div class="card-body p-3" style="max-height: 500px; overflow-y: auto;">
          <form id="notification-form" action="../notification/remove_notification.php" method="post">
          <?php
            $notifications = [];
            $sql = "SELECT id, message, type, recipient, created_at 
                FROM notifications 
                WHERE created_at >= NOW() - INTERVAL 30 DAY 
                ORDER BY created_at DESC";
            $result = $con->query($sql);
            if ($result !== false && $result->num_rows > 0) {
            $all_users_messages = [];
            while ($row = $result->fetch_assoc()) {
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
              $recipient = ($notif['recipient'] == 'all') ? 'All Users' : $notif['recipient'];
              $sneak_peek = (strlen($notif['message']) > 50)
                ? substr($notif['message'], 0, 50) . '...'
                : $notif['message'];
              $created_at = date('Y-m-d H:i:s', strtotime($notif['created_at']));
              $cardClass = "border-info";
              if ($notif['type'] == 'success') {
              $cardClass = "border-success";
              } else if ($notif['type'] == 'warning') {
              $cardClass = "border-warning";
              }
              echo "<div class='card mb-2 $cardClass'>
                  <div class='card-body d-flex align-items-center justify-content-between'>
                  <div class='d-flex align-items-center'>
                    <input type='checkbox' name='indexes[]' value='{$notif['id']}' class='form-check-input me-2' style='width: 1.25rem; height: 1.25rem;'>
                    <a href='#' class='text-decoration-none fs-6' onclick='viewNotification(\"" . htmlspecialchars(urlencode($notif['message'])) . "\", \"{$created_at}\")'>
                    " . htmlspecialchars($sneak_peek) . "
                    </a>
                  </div>
                  <small class='text-muted fs-6'>{$created_at}</small>
                  </div>
                </div>";
            }
            } else {
            echo "<p class='text-center text-muted fs-6'>No notifications available.</p>";
            }
          ?>
          </form>
        </div>
        </div>
      </div>
      </div>
    </section>

    <!-- Modals -->

    <!-- View Notification Modal -->
    <div id="view-notification-modal" class="modal fade" tabindex="-1" aria-labelledby="viewNotificationModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">
        <div class="modal-header text-black" style="background: lightgray;">
        <h5 class="modal-title fs-4" id="viewNotificationModalLabel">Notification Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
          <h6 class="card-subtitle mb-2 text-muted">Message:</h6>
          <p id="full-notification-message" class="card-text" style="word-break: break-word;"></p>
          <h6 class="card-subtitle mt-3 mb-2 text-muted">Date:</h6>
          <p id="notification-date" class="card-text text-muted"></p>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="modal" class="modal fade" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">
        <div class="modal-header py-2">
        <h5 class="modal-title fs-4" id="confirmModalLabel">Confirm Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body fs-6">
        <p id="modal-message"></p>
        </div>
        <div class="modal-footer">
        <button type="button" id="confirm-button" class="btn btn-danger btn-sm" onclick="submitForm()">Confirm</button>
        <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal()">Cancel</button>
        </div>
      </div>
      </div>
    </div>

    <!-- Custom Styles -->
    <style>
      #notification-management {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }
      @media (max-width: 768px) {
      #notification-management .row {
        flex-direction: column;
      }
      #notification-management h2 {
        font-size: 1.5rem;
      }
      }
    </style>

    <!-- Custom Scripts -->
    <script>
      function disableSubmitButton(event) {
      event.preventDefault();
      document.getElementById('submit-button').disabled = true;
      const form = document.getElementById('send-notification-form');
      const formData = new FormData(form);
      
      fetch('../notification/send_notification.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        showModal(data, false);
        document.getElementById('submit-button').disabled = false;
      })
      .catch(error => {
        showModal('Error sending notification: ' + error, false);
        document.getElementById('submit-button').disabled = false;
      });
      }
      function selectAll() {
      document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
      }
      function openModal(action) {
      const modal = new bootstrap.Modal(document.getElementById('modal'));
      document.getElementById('modal-message').textContent = 'Are you sure you want to remove the selected notifications?';
      modal.show();
      document.getElementById('notification-form').action = '../notification/remove_notification.php';
      }
      function closeModal() {
      const modal = bootstrap.Modal.getInstance(document.getElementById('modal'));
      modal.hide();
      }
      function showModal(message, showConfirmButton) {
      const modal = new bootstrap.Modal(document.getElementById('modal'));
      document.getElementById('modal-message').textContent = message;
      document.getElementById('confirm-button').style.display = showConfirmButton ? 'inline-block' : 'none';
      modal.show();
      }
      function submitForm() {
      document.getElementById('notification-form').submit();
      }
      function viewNotification(message, date) {
        // Replace '+' with '%20' so that decodeURIComponent decodes spaces correctly
        message = message.replace(/\+/g, '%20');
        document.getElementById('full-notification-message').textContent = decodeURIComponent(message);
        document.getElementById('notification-date').textContent = date;
        const modal = new bootstrap.Modal(document.getElementById('view-notification-modal'));
        modal.show();
      }
      
      function closeViewNotificationModal() {
      const modal = bootstrap.Modal.getInstance(document.getElementById('view-notification-modal'));
      modal.hide();
      }
    </script>
    </div>

    <!-- Orders Section -->
    <div id="orders" class="section">
      <h1>Customer Orders Management</h1>
      <?php 
       // Define search and sorting variables
       $search_query = $_GET['search'] ?? '';
       $sort_by = $_GET['sort_by'] ?? 'date';
       $order = $_GET['order'] ?? 'ASC';

       // Build the query with search and sorting
       $query = "
       SELECT o.*, u.full_name, u.user_contact, u.user_address, p.product_name, p.product_price, p.product_image1 
       FROM orders o 
       JOIN user_table u ON o.user_id = u.user_id 
       LEFT JOIN products p ON o.product_id = p.product_id 
       WHERE u.full_name LIKE '%$search_query%' OR p.product_name LIKE '%$search_query%'
       ORDER BY " . ($sort_by == 'name' ? "u.full_name" : "o.date") . " $order
       ";
       $result = mysqli_query($con, $query);
       ?>

      <!-- Recent Orders -->
      <div class="table-container">
        <div class="table-header">
          <div>
        <i class="fa fa-clock"></i>
        <span class="h4">Recent Orders</span>
          </div>
          <!-- Sorting container for Recent Orders (server-side) -->
          <div class="sorting-container">
        <a href="?search=<?php echo urlencode($search_query ?? ''); ?>&sort_by=name&order=<?php echo ($sort_by === 'name' && $order === 'ASC') ? 'DESC' : 'ASC'; ?>">Sort by Customer</a>
        <a href="?search=<?php echo urlencode($search_query ?? ''); ?>&sort_by=date&order=<?php echo ($sort_by === 'date' && $order === 'ASC') ? 'DESC' : 'ASC'; ?>">Sort by Date</a>
          </div>
        </div>
        <form method="GET" action="" class="search-form">
          <input type="text" name="search" placeholder="Search by customer or product" 
            value="<?php echo htmlspecialchars($search_query ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
            class="search-input">
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <div class="table-responsive">
          <table class="orders-table">
        <thead>
          <tr>
            <th>Customer</th>
            <th>Product Name</th>
            <th>Contact</th>
            <th class="text-center">Product Price</th>
            <th class="text-center">Quantity</th>
            <th class="text-center">Payment Status</th>
            <th class="text-center">Payment Method</th>
            <th class="text-center">User Address</th>
            <th class="text-center">Order Date</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody id="recent-orders-body">
          <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['user_contact'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['product_price'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="text-center">
              <span class="status status-success"><?php echo htmlspecialchars($row['payment_status'], ENT_QUOTES, 'UTF-8'); ?></span>
            </td>
            <td class="text-center"><?php echo htmlspecialchars($row['payment_method'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['user_address'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td class="text-center">
              <button class="btn btn-primary deliver-btn" 
              data-order-id="<?php echo $row['order_id']; ?>" 
              data-user-id="<?php echo $row['user_id']; ?>">
            Deliver
              </button>
            </td>
          </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="10" class="text-center">No orders found.</td></tr>
          <?php endif; ?>
        </tbody>
          </table>
        </div>
        <a href="generate_pdf.php?table=recent" class="btn-pdf">
          <i class="fas fa-file-pdf"></i> Download Recent Orders as PDF
        </a>
      </div>

      <!-- Delivery Modal -->
      <div class="modal fade" id="deliveryModal" tabindex="-1" aria-labelledby="deliveryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header modal-header-light">
              <h5 class="modal-title" id="deliveryModalLabel">Select Delivery Date</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="deliveryForm">
                <div class="mb-3">
                  <label for="deliveryDate" class="form-label">Delivery Date</label>
                  <input type="date" class="form-control" id="deliveryDate" name="deliveryDate" required>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="submitDeliveryDate">Submit</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Confirmation Modal -->
      <div class="modal fade" id="confirmDeliveryModal" tabindex="-1" aria-labelledby="confirmDeliveryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header modal-header-light">
              <h5 class="modal-title" id="confirmDeliveryModalLabel">Confirm Delivery</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to deliver this order on <span id="confirmDeliveryDate"></span>?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="confirmDeliveryButton">Yes, Deliver</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Shipped Orders Section -->
      <div class="table-container" id="shipped-orders">
        <div class="section-header">
          <div>
        <i class="fas fa-truck"></i>
        <span class="h4">Shipped Orders</span>
          </div>
          <!-- Sorting container for Shipped Orders (client-side) -->
          <div class="sorting-container">
        <button onclick="sortCustomer('shipped')">Sort by Customer</button>
        <button onclick="sortTable('shipped')">Sort by Date</button>
          </div>
        </div>
        <div class="search-form">
          <input type="text" id="shipped-search" placeholder="Search by Customer, Product Name, or Date" class="search-input">
        </div>
        <div class="table-responsive">
          <table class="orders-table">
        <thead>
          <tr>
            <th>Customer</th>
            <th>Product Name</th>
            <th>Contact</th>
            <th class="text-center">Product Price</th>
            <th class="text-center">Quantity</th>
            <th class="text-center">Payment Status</th>
            <th class="text-center">Payment Method</th>
            <th class="text-center">User Address</th>
            <th class="text-center">Delivery Date</th>
          </tr>
        </thead>
        <tbody id="shipped-orders-body">
          <?php
          $query_shipped = "SELECT so.*, u.full_name, u.user_contact, 
                  p.product_name, p.product_price, p.product_image1
                  FROM shipped_orders so 
                  JOIN user_table u ON so.user_id = u.user_id 
                  LEFT JOIN products p ON so.product_id = p.product_id";
          $result_shipped = mysqli_query($con, $query_shipped);
          if ($result_shipped && mysqli_num_rows($result_shipped) > 0):
          while ($row = mysqli_fetch_assoc($result_shipped)):
          ?>
            <tr>
          <td><?php echo htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php echo htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php echo htmlspecialchars($row['user_contact'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td class="text-center"><?php echo htmlspecialchars($row['product_price'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td class="text-center"><?php echo htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td class="text-center">
            <span class="status status-success"><?php echo htmlspecialchars($row['payment_status'], ENT_QUOTES, 'UTF-8'); ?></span>
          </td>
          <td class="text-center"><?php echo htmlspecialchars($row['payment_method'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td class="text-center"><?php echo htmlspecialchars($row['user_address'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td class="text-center delivery-date"><?php echo htmlspecialchars($row['delivery_date'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
          <?php
          endwhile;
          else:
          ?>
            <tr><td colspan="9" class="text-center">No shipped orders found.</td></tr>
          <?php endif; ?>
        </tbody>
          </table>
        </div>
        <a href="generate_pdf.php?table=shipped" class="btn-pdf">
          <i class="fas fa-file-pdf"></i> Download Shipped Orders as PDF
        </a>
      </div>

      <!-- Completed Orders Section -->
      <div class="table-container" id="completed-orders">
        <div class="section-header">
          <div>
            <i class="fas fa-check-circle"></i>
            <span class="h4">Completed Orders</span>
          </div>
          <!-- Sorting container for Completed Orders (client-side) -->
          <div class="sorting-container">
            <button onclick="sortCustomer('completed')">Sort by Customer</button>
            <button onclick="sortTable('completed')">Sort by Date</button>
          </div>
        </div>
        <div class="search-form">
          <input type="text" id="completed-search" placeholder="Search by Customer, Product Name, or Date" class="search-input">
        </div>
        <div class="table-responsive">
          <table class="orders-table">
            <thead>
              <tr>
                <th>Customer</th>
                <th>Product Name</th>
                <th>Contact</th>
                <th class="text-center">Product Price</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Payment Status</th>
                <th class="text-center">User Address</th>
                <th class="text-center">Date Received</th>
              </tr>
            </thead>
            <tbody id="completed-orders-body">
              <?php
              $query_completed = "SELECT co.*, u.full_name, u.user_contact, 
                                        p.product_name, p.product_price, p.product_image1, co.date_received
                                    FROM completed_order co 
                                    JOIN user_table u ON co.user_id = u.user_id 
                                    LEFT JOIN products p ON co.product_id = p.product_id";
              $result_completed = mysqli_query($con, $query_completed);
              if ($result_completed && mysqli_num_rows($result_completed) > 0):
                  while ($row = mysqli_fetch_assoc($result_completed)):
              ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($row['user_contact'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($row['product_price'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td class="text-center"><?php echo htmlspecialchars($row['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td class="text-center">
                    <span class="status status-success"><?php echo htmlspecialchars($row['payment_status'], ENT_QUOTES, 'UTF-8'); ?></span>
                  </td>
                  <td class="text-center"><?php echo htmlspecialchars($row['user_address'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td class="text-center date-received"><?php echo htmlspecialchars($row['date_received'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
              <?php
                  endwhile;
              else:
              ?>
                <tr><td colspan="8" class="text-center">No completed orders found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <a href="generate_pdf.php?table=completed" class="btn-pdf">
          <i class="fas fa-file-pdf"></i> Download Completed Orders as PDF
        </a>
      </div>
       <!-- JavaScript Section -->
        <script src="path/to/bootstrap.bundle.min.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", () => {
        // --- Delivery Modal functionality ---
        const deliverButtons = document.querySelectorAll(".deliver-btn");
        const deliveryModal = document.getElementById("deliveryModal");
        const deliveryDateInput = document.getElementById("deliveryDate");
        const submitDeliveryDateButton = document.getElementById("submitDeliveryDate");
        const confirmDeliveryModal = document.getElementById("confirmDeliveryModal");
        const confirmDeliveryDate = document.getElementById("confirmDeliveryDate");
        const confirmDeliveryButton = document.getElementById("confirmDeliveryButton");

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        deliveryDateInput.setAttribute('min', today);
        deliveryDateInput.value = today;

        // Open delivery modal on click
        deliverButtons.forEach(button => {
            button.addEventListener("click", () => {
            deliveryModal.dataset.orderId = button.dataset.orderId;
            deliveryModal.dataset.userId = button.dataset.userId;
            new bootstrap.Modal(deliveryModal).show();
            });
        });

        submitDeliveryDateButton.addEventListener("click", () => {
            confirmDeliveryDate.textContent = deliveryDateInput.value;
            new bootstrap.Modal(confirmDeliveryModal).show();
        });

        confirmDeliveryButton.addEventListener("click", () => {
            const orderId = deliveryModal.dataset.orderId;
            const userId = deliveryModal.dataset.userId;
            const deliveryDate = deliveryDateInput.value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "save_order.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert("Order saved successfully!");
                    location.reload();
                } else {
                    alert("Error: " + response.message);
                }
                } catch (error) {
                alert("An unexpected error occurred.");
                }
            }
            };
            xhr.send(JSON.stringify({ orderId, userId, deliveryDate }));
        });

        // --- Search and Sort for Shipped and Completed Orders ---
        const shippedSortOrder = { date: true, customer: true };
        const completedSortOrder = { date: true, customer: true };

        window.searchTable = function(type) {
            const searchInput = document.getElementById(type === 'shipped' ? 'shipped-search' : 'completed-search').value.toLowerCase();
            const tableBody = document.getElementById(type === 'shipped' ? 'shipped-orders-body' : 'completed-orders-body');
            Array.from(tableBody.getElementsByTagName('tr')).forEach(row => {
            const found = Array.from(row.getElementsByTagName('td')).some(cell =>
                cell.innerText.toLowerCase().includes(searchInput)
            );
            row.style.display = found ? '' : 'none';
            });
        };

        window.sortTable = function(type) {
            const tableBody = document.getElementById(type === 'shipped' ? 'shipped-orders-body' : 'completed-orders-body');
            const rows = Array.from(tableBody.getElementsByTagName('tr'));
            const selector = type === 'shipped' ? '.delivery-date' : '.date-received';
            const sortOrder = (type === 'shipped') ? shippedSortOrder : completedSortOrder;
            rows.sort((a, b) => {
            const dateA = new Date(a.querySelector(selector).innerText);
            const dateB = new Date(b.querySelector(selector).innerText);
            return sortOrder.date ? dateA - dateB : dateB - dateA;
            });
            tableBody.innerHTML = "";
            rows.forEach(row => tableBody.appendChild(row));
            sortOrder.date = !sortOrder.date;
        };

        window.sortCustomer = function(type) {
            const tableBody = document.getElementById(type === 'shipped' ? 'shipped-orders-body' : 'completed-orders-body');
            const rows = Array.from(tableBody.getElementsByTagName('tr'));
            const sortOrder = (type === 'shipped') ? shippedSortOrder : completedSortOrder;
            rows.sort((a, b) => {
            const nameA = a.cells[0].innerText.toLowerCase();
            const nameB = b.cells[0].innerText.toLowerCase();
            return sortOrder.customer ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
            });
            tableBody.innerHTML = "";
            rows.forEach(row => tableBody.appendChild(row));
            sortOrder.customer = !sortOrder.customer;
        };

        // Attach search event listeners
        document.getElementById('shipped-search').addEventListener('keyup', () => searchTable('shipped'));
        document.getElementById('completed-search').addEventListener('keyup', () => searchTable('completed'));
        });
        </script>
    </div>

    <!-- Products Section -->
    <div id="products" class="section">
    <div class="container my-4">
    <header class="mb-4">
      <h1>Products Management</h1>
      <div class="d-flex flex-wrap mb-3">
        <button type="button" class="btn btn-success btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#addProduct">
          + Add Products
        </button>
        <button type="button" class="btn btn-success btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#addCategory">
          + Add Category
        </button>
        <button type="button" class="btn btn-success btn-sm mx-1" onclick="openCategoryModal()">
          Open Categories
        </button>
      </div>
    </header>

    <!-- Search Bar -->
    <div class="input-group mb-3">
      <span class="input-group-text" id="search-addon">
        <i class="bx bx-search"></i>
      </span>
      <input type="text" id="searchInput" class="form-control" placeholder="Search for products..." onkeyup="filterProducts()" aria-label="Search for products">
    </div>

    <?php
      // Assuming $con is your valid mysqli connection
      $searchTerm = '';
      if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = trim($_GET['search']);
        // Use a prepared statement to prevent SQL injection
        $stmt = $con->prepare("SELECT * FROM products WHERE product_name LIKE ? OR category_title LIKE ?");
        $likeSearch = "%" . $searchTerm . "%";
        $stmt->bind_param("ss", $likeSearch, $likeSearch);
      } else {
        $stmt = $con->prepare("SELECT * FROM products");
      }
      $stmt->execute();
      $result = $stmt->get_result();
    ?>

    <!-- Products Table -->
    <section class="products-table card">
      <div class="card-header d-flex align-items-center">
        <i class="bx bx-box text-primary me-2"></i>
        <h3>Products</h3>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0" id="productsTable">
            <thead  class="sticky-top">
              <tr>
                <th>Product ID</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Category</th>
                <th>Stocks</th>
                <th>Hidden</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): 
                $product_id = $row['product_id'];
                $hidden     = $row['hidden'];
                $stock      = $row['stock'];
              ?>
              <tr>
                <td><?= htmlspecialchars($product_id) ?></td>
                <td>
                  <img src="product_images/<?= htmlspecialchars($row['product_image1']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>" class="img-thumbnail">
                </td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['product_price']) ?></td>
                <td><?= htmlspecialchars($row['category_title']) ?></td>
                <td>
                  <div class="stock-control">
                    <input type="number" id="input_stock_<?= $product_id ?>" value="<?= htmlspecialchars($stock) ?>" class="form-control form-control-sm stock-input" onchange="updateManualStock(<?= $product_id ?>)">
                  </div>
                </td>
                <td>
                  <input type="checkbox" class="form-check-input hidden-checkbox" data-product-id="<?= $product_id ?>" <?= ($hidden === 'YES') ? 'checked' : '' ?>>
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal" data-id="<?= $product_id ?>">
                    Edit
                    </button>
                </td>
              </tr>
              <?php endwhile; 
                    $stmt->close();
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

  <!-- Include Bootstrap JS -->
  <script src="path/to/bootstrap.bundle.min.js"></script>
  <script>
    // Update hidden status when checkbox state changes
    document.querySelectorAll('.hidden-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        const productId = this.dataset.productId;
        const hiddenValue = this.checked ? 'YES' : 'NO';

        fetch('update_hidden_status.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ product_id: productId, hidden: hiddenValue })
        })
        .then(response => response.text())
        .then(data => alert(data))
        .catch(error => console.error('Error:', error));
      });
    });

    // Update stock using AJAX
    function updateManualStock(productId) {
      const stockInput = document.getElementById(`input_stock_${productId}`);
      const updatedStock = stockInput.value;

      fetch('update_stock.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ product_id: productId, stock: updatedStock })
      })
      .then(response => response.json())
      .then(data => {
        if (!data.success) {
          console.error('Failed to update stock:', data.message);
        }
      })
      .catch(error => console.error('Error updating stock:', error));
    }

    // Filter products on keyup in the search input
    function filterProducts() {
      const searchInput = document.getElementById("searchInput").value.toLowerCase();
      const tableRows = document.querySelectorAll("#productsTable tbody tr");

      tableRows.forEach(row => {
        const productName = row.cells[2].textContent.toLowerCase();
        const category    = row.cells[4].textContent.toLowerCase();
        row.style.display = (productName.includes(searchInput) || category.includes(searchInput)) ? "" : "none";
      });
    }

    // Placeholder function for opening the category modal
    function openCategoryModal() {
      console.log('Open Category Modal');
    }
  </script>


    <!-- Add Product Modal -->
    <div class="modal fade add-product-modal" id="addProduct" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addProductLabel">Insert Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert_product'])) {
              // Check if product_category is set
              if (!isset($_POST['product_category']) || empty($_POST['product_category'])) {
                echo "<script>alert('Please select a category.');</script>";
              } else {
                $product_name = $_POST['product_name'];
                $product_description = $_POST['product_description'];
                $product_description2 = $_POST['product_description2'];
                $product_keyword = $_POST['product_keyword'];
                $product_category = $_POST['product_category'];
                $product_price = $_POST['product_price'];
                $promo = $_POST['promo'];

                // For images
                $product_image1 = $_FILES['product_image1']['name'];
                $product_image2 = $_FILES['product_image2']['name'];
                $product_image3 = $_FILES['product_image3']['name'];
                $product_image4 = $_FILES['product_image4']['name'];

                // Temporary image paths
                $temp_image1 = $_FILES['product_image1']['tmp_name'];
                $temp_image2 = $_FILES['product_image2']['tmp_name'];
                $temp_image3 = $_FILES['product_image3']['tmp_name'];
                $temp_image4 = $_FILES['product_image4']['tmp_name'];

                // Check for required fields
                if ($product_name == '' || $product_description == '' || $product_keyword == '' || $product_price == '' || $product_image1 == '' || $promo == '') {
                  echo "<script>alert('Please fill all the available fields.');</script>";
                } else {
                  // Move uploaded files
                  move_uploaded_file($temp_image1, "./product_images/$product_image1");
                  move_uploaded_file($temp_image2, "./product_images/$product_image2");
                  move_uploaded_file($temp_image3, "./product_images/$product_image3");
                  move_uploaded_file($temp_image4, "./product_images/$product_image4");

                  // Prepare and execute the SQL statement
                  $insert_products = $con->prepare("INSERT INTO `products` (product_name, product_description, product_description2, product_keyword, category_title, product_image1, product_image2, product_image3, product_image4, product_price, promo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                  $insert_products->bind_param("ssssssssdsd", $product_name, $product_description, $product_description2, $product_keyword, $product_category, $product_image1, $product_image2, $product_image3, $product_image4, $product_price, $promo);

                  if ($insert_products->execute()) {
                    // Redirect to avoid form resubmission on page refresh
                    echo "<script>window.location.href = window.location.href;</script>";
                    exit(); // Ensure no further code execution after the redirect
                  } else {
                    echo "<script>alert('Error inserting product: " . $insert_products->error . "');</script>";
                  }

                  $insert_products->close();
                }
              }
            }
            ?>

            <!-- Product Insert Form -->
            <form action="" method="post" enctype="multipart/form-data" class="add-product-form">
              <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter Product Name" autocomplete="off" required>
              </div>
              <div class="mb-3">
                <label for="product_description" class="form-label">Product Description</label>
                <textarea name="product_description" id="product_description" class="form-control" placeholder="Enter Product Description" required></textarea>
              </div>
              <div class="mb-3">
                <label for="product_description2" class="form-label">Product Size Description</label>
                <textarea name="product_description2" id="product_description2" class="form-control" placeholder="Enter Product Size Description" required></textarea>
              </div>
              <div class="mb-3">
                <label for="product_keyword" class="form-label">Product Keywords</label>
                <input type="text" name="product_keyword" id="product_keyword" class="form-control" placeholder="Enter Product Keywords" autocomplete="off" required>
              </div>
              <div class="mb-3">
                <label for="promo" class="form-label">Promo</label>
                <input type="text" name="promo" id="promo" class="form-control" placeholder="Enter Promo Details" autocomplete="off" required>
              </div>
              <div class="mb-3">
                <label for="product_category" class="form-label">Select Category</label>
                <select name="product_category" id="product_category" class="form-select" required>
                  <option value="">Select Category</option>
                  <?php 
                  $select_query = "SELECT * FROM `categories`";
                  $result_query = mysqli_query($con, $select_query);
                  while ($row = mysqli_fetch_assoc($result_query)) {
                    $category_title = $row['category_title'];
                    echo "<option value='$category_title'>$category_title</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="product_image1" class="form-label">Product Image 1</label>
                <input type="file" name="product_image1" id="product_image1" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="product_image2" class="form-label">Product Image 2</label>
                <input type="file" name="product_image2" id="product_image2" class="form-control">
              </div>
              <div class="mb-3">
                <label for="product_image3" class="form-label">Product Image 3</label>
                <input type="file" name="product_image3" id="product_image3" class="form-control">
              </div>
              <div class="mb-3">
                <label for="product_image4" class="form-label">Product Image 4</label>
                <input type="file" name="product_image4" id="product_image4" class="form-control">
              </div>
              <div class="mb-3">
                <label for="product_price" class="form-label">Product Price</label>
                <input type="number" name="product_price" id="product_price" class="form-control" placeholder="Enter Product Price" required>
              </div>
              <button type="submit" name="insert_product" class="btn btn-success w-100">Add Product</button>
            </form>
          </div>
        </div>
      </div>
    </div>

        
    <!-- Edit Product Modal -->
    <div class="modal fade edit-product-modal" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #1976D2, #0d47a1);">
        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form action="update_product.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="product_id" id="product_id_edit">
          
          <!-- Hidden fields for existing images -->
          <?php foreach (['product_image1', 'product_image2', 'product_image3', 'product_image4'] as $image_field): ?>
          <input type="hidden" name="existing_<?= $image_field ?>" id="existing_<?= $image_field ?>">
          <?php endforeach; ?>

          <!-- Product Information -->
          <div class="row mb-3">
          <div class="col-md-6">
            <label for="product_name_edit" class="form-label">Product Name</label>
            <input type="text" name="product_name" id="product_name_edit" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="product_price_edit" class="form-label">Product Price</label>
            <input type="number" step="0.01" name="product_price" id="product_price_edit" class="form-control" required>
          </div>
          </div>
          
          <!-- Descriptions -->
          <div class="mb-3">
          <label for="product_description_edit" class="form-label">Product Description</label>
          <textarea name="product_description" id="product_description_edit" class="form-control" required></textarea>
          </div>
          <div class="mb-3">
          <label for="product_description2_edit" class="form-label">Product Size Description</label>
          <textarea name="product_description2" id="product_description2_edit" class="form-control"></textarea>
          </div>
          
          <!-- Promo Section -->
          <div class="mb-3">
          <label for="promo_edit" class="form-label">Promo/Discount</label>
          <input type="text" name="promo" id="promo_edit" class="form-control">
          </div>
          
          <!-- Keywords and Category -->
          <div class="row mb-3">
          <div class="col-md-6">
            <label for="product_keyword_edit" class="form-label">Product Keyword</label>
            <input type="text" name="product_keyword" id="product_keyword_edit" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label for="product_category_edit" class="form-label">Select Category</label>
            <select name="product_category" id="product_category_edit" class="form-select" required>
            <option value="">Select Category</option>
            <?php 
            $query = "SELECT * FROM categories";
            $result = mysqli_query($con, $query);
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<option value='" . htmlspecialchars($row['category_id']) . "'>" . htmlspecialchars($row['category_title']) . "</option>";
            }
            ?>
            </select>
          </div>
          </div>
          
          <!-- Current Images -->
          <div class="row g-3">
          <?php foreach (['product_image1', 'product_image2', 'product_image3', 'product_image4'] as $key => $image_field): ?>
            <div class="col-md-6">
            <label class="form-label">Current Image <?= $key + 1; ?></label><br>
            <img id="current_<?= $image_field ?>_preview" src="" class="current-image mb-2" style="max-width: 150px; max-height: 150px;">
            <input type="file" name="<?= $image_field ?>" class="form-control">
            <small class="text-muted">Leave empty to keep current image</small>
            </div>
          <?php endforeach; ?>
          </div>
          
          <!-- Action Buttons -->
          <div class="text-end mt-4">
          <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go Back</button>
          </div>
        </form>
        </div>
      </div>
      </div>
    </div>

    <script>
    $(document).ready(function() {
      $('#editProductModal').on('show.bs.modal', function(e) {
      var modal = $(this);
      var productId = $(e.relatedTarget).data('id');
      
      // Show loading state
      modal.find('input, select, textarea').prop('disabled', true);
      modal.find('.modal-title').html('Loading Product...');
      
      $.ajax({
        url: 'fetch_products.php',
        type: 'GET',
        data: { product_id: productId },
        dataType: 'json',
        success: function(response) {
        if(response.error) {
          alert(response.error);
          modal.modal('hide');
          return;
        }
        
        // Populate form fields
        $('#product_id_edit').val(response.product_id);
        $('#product_name_edit').val(response.product_name);
        $('#product_price_edit').val(response.product_price);
        $('#product_description_edit').val(response.product_description);
        $('#product_description2_edit').val(response.product_description2 || '');
        $('#product_keyword_edit').val(response.product_keyword);
        $('#promo_edit').val(response.promo || '');
        $('#product_category_edit').val(response.category_title);
        
        // Handle images
        ['product_image1', 'product_image2', 'product_image3', 'product_image4'].forEach((field, index) => {
          const imgElement = $(`#current_${field}_preview`);
          const hiddenField = $(`#existing_${field}`);
          
          if(response[field]) {
          imgElement.attr('src', 'product_images/' + response[field]);
          hiddenField.val(response[field]);
          } else {
          imgElement.attr('src', '');
          hiddenField.val('');
          }
        });
        
        // Enable form
        modal.find('input, select, textarea').prop('disabled', false);
        modal.find('.modal-title').html('Edit Product');
        },
        error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error);
        alert('Failed to load product data');
        modal.modal('hide');
        }
      });
      });
    });
    </script>



    <!-- Manage Category -->
    <?php
    // Handle category deletion
    if (isset($_POST['delete_id'])) {
      $delete_id = intval($_POST['delete_id']);
      
      // Prepare and execute the deletion query
      $delete_query = "DELETE FROM categories WHERE category_id = ?";
      $stmt = mysqli_prepare($con, $delete_query);
      mysqli_stmt_bind_param($stmt, "i", $delete_id);
      
      if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Category deleted successfully!');</script>";
        echo "<script>window.location.href='adminpanel.php';</script>";
      } else {
        echo "<script>alert('Failed to delete category: " . mysqli_error($con) . "');</script>";
      }
      mysqli_stmt_close($stmt);
    }

    // Fetch categories
    $sql = "SELECT * FROM categories";
    $result = mysqli_query($con, $sql);
    $categories = [];
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
      }
    }
    ?>
      <!-- Modal for Categories List -->
      <div class="category-modal-container" id="categoryModal">
      <div class="category-modal-content">
        <button class="category-close-btn" onclick="closeCategoryModal()">&times;</button>
        <div class="category-modal-header">
        <h2>Category List</h2>
        </div>
        <div class="category-table-wrapper">
        <table class="category-table">
          <thead>
          <tr>
            <th>Category ID</th>
            <th>Category Title</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($categories as $category): ?>
            <tr>
            <td><?= htmlspecialchars($category['category_id']) ?></td>
            <td><?= htmlspecialchars($category['category_title']) ?></td>
            <td>
              <button class="category-delete-btn" onclick="openCategoryConfirmModal(<?= $category['category_id'] ?>)">Remove</button>
            </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        </div>
      </div>
      </div>

      <!-- Confirmation Modal -->
      <div class="category-confirm-modal-container" id="confirmModal">
        <div class="category-confirm-modal-content">
          <div class="category-confirm-modal-header">
            <h3>Confirm Deletion</h3>
            <button class="category-close-btn" onclick="closeCategoryConfirmModal()">&times;</button>
          </div>
          <p style="text-align: center;">Are you sure you want to delete this category?</p>
          <form method="POST" style="text-align: center;">
            <input type="hidden" name="delete_id" id="delete_id">
            <div class="category-button-group">
              <button type="submit" class="category-confirm-btn">Yes, Delete</button>
              <button type="button" class="category-cancel-btn" onclick="closeCategoryConfirmModal()">Cancel</button>
            </div>
          </form>
        </div>
      </div>

      <script>
        // Ensure modals are hidden on page load
        document.addEventListener('DOMContentLoaded', function () {
          closeCategoryModal();
          closeCategoryConfirmModal();
        });

        // Open the categories modal
        function openCategoryModal() {
          document.getElementById('categoryModal').style.display = 'flex';
        }

        // Close the categories modal
        function closeCategoryModal() {
          document.getElementById('categoryModal').style.display = 'none';
        }

        // Open the category confirmation modal
        function openCategoryConfirmModal(categoryId) {
          document.getElementById('delete_id').value = categoryId;
          document.getElementById('confirmModal').style.display = 'flex';
        }

        // Close the category confirmation modal
        function closeCategoryConfirmModal() {
          document.getElementById('confirmModal').style.display = 'none';
        }
      </script>

        <!-- Add Category Modal -->
        <div class="modal fade add-category-modal" id="addCategory" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addCategoryLabel">Add Product Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="" method="post" id="addCategoryForm">
                  <div class="mb-3">
                    <input type="text" name="cat_title" class="form-control" placeholder="Category Name" required>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="insert_cat">Add Category</button>
                  </div>
                </form>
                <?php
                if (isset($_POST['insert_cat'])) {
                    $category_title = trim($_POST['cat_title']);
                    // Check if the category already exists using a prepared statement
                    $stmt = mysqli_prepare($con, "SELECT * FROM categories WHERE category_title = ?");
                    mysqli_stmt_bind_param($stmt, "s", $category_title);
                    mysqli_stmt_execute($stmt);
                    $result_select = mysqli_stmt_get_result($stmt);
                    
                    if (mysqli_num_rows($result_select) > 0) {
                        echo "<script>alert('This category already exists');</script>";
                    } else {
                        // Insert the new category using a prepared statement
                        $stmt_insert = mysqli_prepare($con, "INSERT INTO categories (category_title) VALUES (?)");
                    mysqli_stmt_bind_param($stmt_insert, "s", $category_title);
                    if (mysqli_stmt_execute($stmt_insert)) {
                        echo "<script>alert('Successfully added category'); window.location.href = window.location.href;</script>";
                    } else {
                        echo "<script>alert('Failed to add category: " . mysqli_error($con) . "');</script>";
                    }
                    mysqli_stmt_close($stmt_insert);
                }
                mysqli_stmt_close($stmt);
            }
            ?>
          </div>
        </div>
      </div>
    </div>

    </div>

    <!-- Messages Section -->
    <div id="messages" class="section">
       <!-- Users messages -->
      <?php
      if (!isset($_SESSION['admin_username']) || empty($_SESSION['admin_username'])) {
        header("Location: login.php"); // Redirect if not an admin
        exit();
      }

      // Database connection
      $con = new mysqli('localhost', 'root', '', 'rswoodworks');
      if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
      }
      ?>
      <div id="chat-container">
        <div id="user-list">
          <?php
          $userQuery = "SELECT u.user_id, u.full_name, u.user_image, 
                  (SELECT m.message FROM messages m WHERE m.sender_id = u.user_id OR m.receiver_id = u.user_id ORDER BY m.timestamp DESC LIMIT 1) AS last_message 
                  FROM user_table u 
                  JOIN messages m ON u.user_id = m.sender_id OR u.user_id = m.receiver_id 
                  WHERE m.receiver_type = 'admin' 
                  GROUP BY u.user_id";
          $userResult = $con->query($userQuery);
          while ($user = $userResult->fetch_assoc()) {
            $user_id = $user['user_id'];
            $full_name = htmlspecialchars($user['full_name']);
            $user_image = $user['user_image'] ? $user['user_image'] : 'defaultuser.png'; // Default image
            $last_message = htmlspecialchars($user['last_message']);

            echo "<div class='user' data-user-id='$user_id' onclick='selectUser($user_id, \"$full_name\")'>
                <img src='../user/" . htmlspecialchars($user_image) . "' alt='User Image'>
                <div class='user-info'>
                  <span class='user-name'>$full_name</span>
                  <span class='user-message'>$last_message</span>
                </div>
              </div>";
          }
          ?>
        </div>

        <div id="chat-content">
          <div id="chat-header">Messages</div>
          <div id="chat-box">
            <!-- Messages dynamically loaded here -->
          </div>
          <div id="chat-input-container">
            <textarea id="chat-input" placeholder="Type your message..." rows="1"></textarea>
            <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
          </div>
        </div>
      </div>
    </div>

    <style>
      .user {
        display: flex;
        align-items: center;
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
      }
      .user:hover {
        background-color: #f1f1f1;
      }
      .user img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
      }
      .user-info {
        display: flex;
        flex-direction: column;
      }
      .user-name {
        font-weight: bold;
      }
      .user-message {
        font-size: 0.9em;
        color: #666;
      }
      #chat-content {
        width: 70%;
        display: flex;
        flex-direction: column;
      }
      #chat-header {
        padding: 10px;
        background-color: #f5f5f5;
        border-bottom: 1px solid #ccc;
        font-weight: bold;
      }
      #chat-box {
        flex-grow: 1;
        padding: 10px;
        overflow-y: auto;
      }
      #chat-input-container {
        display: flex;
        padding: 10px;
        border-top: 1px solid #ccc;
      }
      #chat-input {
        flex-grow: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: none;
      }
      #chat-input:focus {
        outline: none;
        border-color: #007bff;
      }
      #chat-input-container button {
        margin-left: 10px;
        padding: 10px 15px;
        background-color: #007bff;
        border: none;
        color: white;
        border-radius: 5px;
        cursor: pointer;
      }
      #chat-input-container button:hover {
        background-color: #0056b3;
      }
    </style>

    <script>
      let selectedUserId = null;

      function selectUser(userId, username) {
        selectedUserId = userId;
        document.getElementById("chat-header").textContent = "Chat with " + username;
        fetchMessages(); // Load messages for the selected user
      }

      function fetchMessages() {
        if (!selectedUserId) return;

        fetch(`../chatroom/fetch_admin_messages.php?user_id=${selectedUserId}`)
          .then(response => response.text())
          .then(data => {
            document.getElementById("chat-box").innerHTML = data;
            document.getElementById("chat-box").scrollTop = document.getElementById("chat-box").scrollHeight;
          })
          .catch(error => console.error("Error fetching messages:", error));
      }

      function sendMessage() {
        const message = document.getElementById("chat-input").value;
        if (message.trim() === "" || selectedUserId === null) return;

        fetch("../chatroom/send_admin_message.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `message=${encodeURIComponent(message)}&receiver_id=${selectedUserId}`
        })
        .then(response => response.text())
        .then(data => {
          console.log(data); // For debugging
          document.getElementById("chat-input").value = ""; // Clear input
          fetchMessages(); // Refresh messages
        })
        .catch(error => console.error("Error sending message:", error));
      }
    </script>

      <div id="users" class="section">
      <?php

      // Fetch all users from the database
      $query = "SELECT * FROM user_table";
      $result = mysqli_query($con, $query);
      $users = [];
      while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
      }
      ?>
      <style>
      /* Scoped to #users to avoid interference with other parts of your site */
      #users {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f7f7f7;
      }
      #users h1 {
        font-size: 32px;
        margin-bottom: 20px;
        color: #333;
      }
      /* Card (container) styles */
      #users .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
      }
      #users .card-header {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        padding: 15px 20px;
      }
      #users .card-body {
        padding: 20px;
      }
      /* Search Bar */
      #users #searchInput {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
      }
      /* Table Styles */
      #users table {
        margin-top: 20px;
        width: 100%;
      }
      #users table th, 
      #users table td {
        vertical-align: middle;
        padding: 12px;
      }
      #users table tbody tr:hover {
        background-color: #f1f1f1;
      }
      /* Button Spacing */
      #users .btn {
        margin-bottom: 5px;
        width: 100px;
      }
      /* Modal Styles */
      #users .modal-content {
        border-radius: 5px;
      }
      #users .modal-header {
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
      }
      /* Hide the default close icon inside modals if needed */
      .modal .close {
        display: none;
      }
      /* Style user images in the table */
      #users .user-img {
        max-width: 65px;
        border-radius: 50%;
      }
    </style>
      <div class="container">
        <h1>User Management</h1>
        <div class="card">
          <div class="card-header bg-primary text-white">
            <div class="d-flex align-items-center">
              <i class="bx bx-user mr-2" style="font-size: 24px;"></i>
              <span>Users List</span>
            </div>
          </div>
          <div class="card-body">
            <!-- Search Bar -->
            <div class="form-group">
              <input type="text" id="searchInput" class="form-control" placeholder="Search for users..." onkeyup="filterUsers()">
            </div>
            <!-- Users Table -->
            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="userTable">
                <thead class="thead-light">
                  <tr>
                    <th>User ID</th>
                    <th>User Image</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>User Email</th>
                    <th>User Contact</th>
                    <th>User Address</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($users as $row): ?>
                  <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td>
                      <?php if (!empty($row['user_image'])): ?>
                      <img src="../user/<?php echo $row['user_image']; ?>" alt="User Image" class="user-img img-fluid">
                      <?php else: ?>
                      <span class="text-muted">No image</span>
                      <?php endif; ?>
                    </td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['user_email']; ?></td>
                    <td><?php echo $row['user_contact']; ?></td>
                    <td><?php echo $row['user_address']; ?></td>
                    
                    <td>
                      <!-- Edit Button -->
                      <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editUserModal_<?php echo $row['user_id']; ?>">
                        <i class="bx bx-edit"></i> Edit
                      </button>
                      <!-- Delete Button -->
                      <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteUserModal_<?php echo $row['user_id']; ?>">
                        <i class="bx bx-user-x"></i> Delete
                      </button>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- Success Modal for Update/Delete operations -->
        <div class="modal fade" id="successModal2" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content border-success">
              <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                Operation completed successfully.
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit and Delete Modals for each user -->
      <?php foreach ($users as $row): ?>
      <!-- Edit User Modal -->
      <div class="modal fade" id="editUserModal_<?php echo $row['user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel_<?php echo $row['user_id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <form onsubmit="updateUser(event, <?php echo $row['user_id']; ?>)" class="needs-validation" novalidate>
            <div class="modal-content">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editUserModalLabel_<?php echo $row['user_id']; ?>">Edit User</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                <div class="form-group">
                  <label for="username_<?php echo $row['user_id']; ?>">Username</label>
                  <input type="text" class="form-control" id="username_<?php echo $row['user_id']; ?>" name="username" value="<?php echo $row['username']; ?>" required>
                  <div class="invalid-feedback">Please provide a username.</div>
                </div>
                <div class="form-group">
                  <label for="user_email_<?php echo $row['user_id']; ?>">Email</label>
                  <input type="email" class="form-control" id="user_email_<?php echo $row['user_id']; ?>" name="user_email" value="<?php echo $row['user_email']; ?>" required>
                  <div class="invalid-feedback">Please provide a valid email.</div>
                </div>
                <div class="form-group">
                  <label for="user_contact_<?php echo $row['user_id']; ?>">Contact</label>
                  <input type="text" class="form-control" id="user_contact_<?php echo $row['user_id']; ?>" name="user_contact" value="<?php echo $row['user_contact']; ?>">
                </div>
                <div class="form-group">
                  <label for="user_address_<?php echo $row['user_id']; ?>">Address</label>
                  <input type="text" class="form-control" id="user_address_<?php echo $row['user_id']; ?>" name="user_address" value="<?php echo $row['user_address']; ?>">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Delete User Modal -->
      <div class="modal fade" id="deleteUserModal_<?php echo $row['user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel_<?php echo $row['user_id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <form onsubmit="deleteUser(event, <?php echo $row['user_id']; ?>)">
            <div class="modal-content">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModalLabel_<?php echo $row['user_id']; ?>">Delete User</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                Are you sure you want to delete this user?
                <input type="hidden" name="userId" value="<?php echo $row['user_id']; ?>">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
      // Bootstrap form validation
      (function() {
        'use strict';
        window.addEventListener('load', function() {
          var forms = document.getElementsByClassName('needs-validation');
          Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();

      // Improved search filter using a standard loop for better compatibility
      function filterUsers() {
        var input = document.getElementById('searchInput').value.toLowerCase();
        var table = document.getElementById('userTable');
        var tbody = table.getElementsByTagName('tbody')[0];
        var rows = tbody.getElementsByTagName('tr');
        for (var i = 0; i < rows.length; i++) {
          var rowText = rows[i].textContent.toLowerCase();
          if (rowText.indexOf(input) > -1) {
            rows[i].style.display = "";
          } else {
            rows[i].style.display = "none";
          }
        }
      }

      // Update user information via AJAX
      function updateUser(event, userId) {
        event.preventDefault();
        var form = event.target;
        var formData = new FormData(form);

        fetch('update_user.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            $('#editUserModal_' + userId).modal('hide');
            $('#successModal2').modal('show');
            setTimeout(() => location.reload(), 1000);
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => console.error('Error:', error));
      }

      // Delete user via AJAX
      function deleteUser(event, userId) {
        event.preventDefault();
        var form = event.target;
        var formData = new FormData(form);

        fetch('../user/delete_user.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            $('#deleteUserModal_' + userId).modal('hide');
            $('#successModal2').modal('show');
            setTimeout(() => location.reload(), 1000);
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => console.error('Error:', error));
      }
    </script>

    <!-- Admin Management Section -->
    <div id="admin" class="section">
      <h1>Admin Management</h1>
      <?php
      require_once 'admin_functions.php';
      checkRole('super_admin');
      $admins = fetchAdmins();
      ?>
      <!-- Scoped CSS for Admin Management -->
      <style>
      /* Scoped to #admin */
      #admin {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f4f9;
        padding: 20px;
      }
      #admin h1 {
        font-size: 25px;
        color: #333;
      }
      /* Admin actions */
      #admin .admin-actions {
        text-align: right;
        margin-bottom: 20px;
      }
      #admin .admin-actions .btn {
        font-size: 16px;
        padding: 10px 20px;
      }
      /* Bottom data card */
      #admin .bottom-data {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }
      #admin h2 {
        font-size: 28px;
        margin-bottom: 20px;
        color: #007bff;
        display: flex;
        align-items: center;
      }
      #admin h2 i {
        margin-right: 10px;
        font-size: 28px;
      }
      /* Search Bar */
      #admin #searchInput {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 12px;
        font-size: 16px;
        width: 100%;
        max-width: 400px;
        margin-bottom: 20px;
      }
      /* Table styles */
      #admin table {
        width: 100%;
        border-collapse: collapse;
      }
      #admin table th,
      #admin table td {
        padding: 12px;
        vertical-align: middle;
        text-align: center;
        border: 1px solid #dee2e6;
      }
      #admin table thead {
        background-color: #007bff;
        color: #fff;
      }
      #admin table tbody tr:hover {
        background-color: #f1f1f1;
      }
      /* Action buttons */
      #admin .action-buttons button {
        margin-right: 5px;
        font-size: 14px;
        padding: 8px 12px;
      }
      /* Modal styles */
      #admin .modal-content {
        border-radius: 10px;
      }
      #admin .modal-header {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
      }
      #admin .modal-header .close {
        font-size: 24px;
      }
      /* Form controls */
      #admin .form-control {
        font-size: 16px;
        padding: 10px;
      }
      #admin .btn-primary,
      #admin .btn-secondary,
      #admin .btn-danger {
        font-size: 16px;
        padding: 10px 20px;
      }
      </style>
      <div class="container">
        <div class="admin-actions mb-3 d-flex justify-content-start">
        <a href="../user/login.php" class="btn btn-success">Add New Admin</a>
        </div>
        <div class="bottom-data">
        <h2 class="text-primary"><i class="bx bx-user"></i> Admin List</h2>
        <!-- Search bar -->
        <input type="text" id="searchInput" onkeyup="filterAdmins()" placeholder="Search for admins..." class="form-control mb-3">
        <!-- Admin Table -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="adminTable">
          <thead class="thead-light">
        <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Contact</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
        </tr>
          </thead>
          <tbody>
        <?php while ($admin = mysqli_fetch_assoc($admins)) { ?>
        <tr>
        <td><?php echo htmlspecialchars($admin['admin_id']); ?></td>
        <td><?php echo htmlspecialchars($admin['username']); ?></td>
        <td><?php echo htmlspecialchars($admin['full_name']); ?></td>
        <td><?php echo htmlspecialchars($admin['admin_contact']); ?></td>
        <td><?php echo htmlspecialchars($admin['admin_email']); ?></td>
        <td><?php echo htmlspecialchars($admin['role']); ?></td>
        <td class="action-buttons">
          <button type="button" class="btn btn-primary btn-sm" onclick="openEditModal(<?php echo $admin['admin_id']; ?>)">Edit</button>
          <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal(<?php echo $admin['admin_id']; ?>)">Delete</button>
        </td>
        </tr>
        <?php } ?>
          </tbody>
          </table>
        </div>
        </div>
      </div>
      </div>

      <!-- Edit Admin Modal -->
      <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="editAdminLabel">Edit Admin Information</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editAdminForm" action="update_admin.php" method="post" onsubmit="return validatePasswords()">
          <input type="hidden" id="adminId" name="adminId">
          <div class="mb-3">
        <label for="editUsername" class="form-label">Username</label>
        <input type="text" class="form-control" id="editUsername" name="username" required>
          </div>
          <div class="mb-3">
        <label for="editFullName" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="editFullName" name="fullName" required>
          </div>
          <div class="mb-3">
        <label for="editEmail" class="form-label">Email</label>
        <input type="email" class="form-control" id="editEmail" name="adminEmail" required>
          </div>
          <div class="mb-3">
        <label for="editContact" class="form-label">Contact</label>
        <input type="text" class="form-control" id="editContact" name="adminContact" required>
          </div>
          <!-- New password fields hidden by default -->
          <div id="passwordFields" style="display: none;">
        <div class="mb-3">
        <label for="editPassword" class="form-label">New Password</label>
        <input type="password" class="form-control" id="editPassword" name="password">
        </div>
        <div class="mb-3">
        <label for="editConfirmPassword" class="form-label">Retype New Password</label>
        <input type="password" class="form-control" id="editConfirmPassword" name="confirmPassword">
        </div>
          </div>
          <!-- Toggle password fields -->
          <button type="button" class="btn btn-secondary mb-3" onclick="togglePasswordFields()">Change Password</button>
          <div class="mb-3">
        <label for="editRole" class="form-label">Role</label>
        <select class="form-control" id="editRole" name="role">
        <option value="super_admin">Super Admin</option>
        <option value="manager">Manager</option>
        <option value="editor">Editor</option>
        </select>
          </div>
          <button type="submit" class="btn btn-primary">Update Admin</button>
          </form>
        </div>
        </div>
      </div>
      </div>

      <!-- Admin Deletion Confirmation Modal -->
      <div class="modal fade" id="deleteAdminModal" tabindex="-1" aria-labelledby="deleteAdminLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="deleteAdminLabel">Confirm Admin Deletion</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this admin? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <a id="confirmDeleteBtn" href="" class="btn btn-danger">Delete</a>
        </div>
        </div>
      </div>
      </div>

      <!-- JavaScript Code -->
      <script>
      function openEditModal(adminId) {
        // Fetch admin details and populate the form
        fetch(`get_admin_details.php?id=${adminId}`)
        .then(response => response.json())
        .then(data => {
          document.getElementById('adminId').value = data.admin_id;
          document.getElementById('editUsername').value = data.username;
          document.getElementById('editFullName').value = data.full_name;
          document.getElementById('editContact').value = data.admin_contact;
          document.getElementById('editEmail').value = data.admin_email;
          document.getElementById('editPassword').value = '';
          document.getElementById('editConfirmPassword').value = '';
          document.getElementById('editRole').value = data.role;
          // Hide password fields initially
          document.getElementById('passwordFields').style.display = 'none';
          $('#editAdminModal').modal('show');
        });
      }

      function togglePasswordFields() {
        const passwordFields = document.getElementById('passwordFields');
        passwordFields.style.display = (passwordFields.style.display === 'none') ? 'block' : 'none';
      }

      function filterAdmins() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const table = document.getElementById('adminTable');
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(input) ? '' : 'none';
        });
      }

      function validatePasswords() {
        const password = document.getElementById('editPassword').value;
        const confirmPassword = document.getElementById('editConfirmPassword').value;
        if (password !== confirmPassword) {
        alert('Passwords do not match. Please retype the password.');
        return false;
        }
        return true;
      }

      function showDeleteModal(adminId) {
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        deleteBtn.href = `delete_admin.php?id=${adminId}`;
        $('#deleteAdminModal').modal('show');
      }
      </script>
        </div>


  <!-- Scripts -->
  <script>
    // Function to show only one section at a time using CSS classes
    function showSection(sectionId) {
      const sections = document.querySelectorAll('.section');
      const links = document.querySelectorAll('.side-menu li a');
      
      // Remove active class from all sections and links
      sections.forEach(section => section.classList.remove('active'));
      links.forEach(link => link.classList.remove('active'));
      
      // Add active class to the selected section and corresponding link
      const activeSection = document.getElementById(sectionId);
      if (activeSection) {
        activeSection.classList.add('active');
      } else {
        console.error("Section not found for ID:", sectionId);
      }
      
      const activeLink = document.getElementById('link-' + sectionId);
      if (activeLink) {
        activeLink.classList.add('active');
      }
      
      // Save the active section in localStorage
      localStorage.setItem('activeSection', sectionId);
    }

    // Combine DOMContentLoaded actions into one event listener
    document.addEventListener('DOMContentLoaded', function() {
      // Restore last active section or default to 'dashboard'
      const storedSection = localStorage.getItem('activeSection') || 'dashboard';
      showSection(storedSection);

      // Function to handle sidebar collapse for mobile view
      function checkWindowSize() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggle-btn');
        if (window.innerWidth <= 768) {
          sidebar.classList.add('collapsed');
          toggleBtn.style.display = 'block';
        } else {
          sidebar.classList.remove('collapsed');
          toggleBtn.style.display = 'none';
        }
      }
      checkWindowSize();
      window.addEventListener('resize', checkWindowSize);

      // Toggle button inside sidebar (for mobile view)
      document.getElementById('toggle-btn').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('collapsed');
      });

      // Mobile header toggle button to open/close sidebar
      document.getElementById('mobile-toggle-btn').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
      });
    });
  </script>
</body>
</html>
