<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="notif.css">
    <title>View Notification</title>
</head>
<body>
    <?php
    if (isset($_GET['message'])) {
        $message = $_GET['message'];
        echo "<div class='notification'>
                <p>$message</p>
              </div>";
    } else {
        echo "<p>No notification to display.</p>";
    }
    ?>
</body>
</html>
