<?php
include '../includes/connection.php';
include '../includes/functions.php';
session_start();

// Redirect logged-in users to the appropriate page
if (isset($_SESSION['username'])) {
    header("Location: ../home.php");
    exit;
}

// Initialize variables
$username_error = "";
$registration_status = "";
$show_signup_tab = false; // Added to control signup tab visibility

// Helper function to check user role
function hasRequiredRole($required_role) {
    if (!isset($_SESSION['admin_username']) && !isset($_SESSION['username'])) {
        return false;
    }
    $username = $_SESSION['admin_username'] ?? $_SESSION['username'];
    include '../includes/connection.php';
    $query = "SELECT role FROM admin_table WHERE username=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return ($row['role'] === $required_role);
    }
    return false;
}

// Check if the current user is a super_admin
$isSuperAdmin = hasRequiredRole('super_admin');

// Autoload Composer dependencies
require __DIR__ . '/../vendor/autoload.php';

// Include Firebase Admin SDK
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

$firebase = (new Factory)
    ->withServiceAccount(__DIR__ . '/../config/firebase-admin-sdk.json')
    ->createAuth();
$auth = $firebase;

// ---------------------
// USER REGISTRATION
// ---------------------
$username = $password = $full_name = $user_email = $user_contact = $user_ip = "";
$password_confirm = "";

if (isset($_POST['user_register'])) {
    $username = trim(mysqli_real_escape_string($con, $_POST['username']));
    $password = trim(mysqli_real_escape_string($con, $_POST['password']));
    $password_confirm = trim(mysqli_real_escape_string($con, $_POST['password_confirm']));
    $full_name = trim(mysqli_real_escape_string($con, $_POST['full_name']));
    $user_email = trim(mysqli_real_escape_string($con, $_POST['user_email']));
    $user_contact = trim(mysqli_real_escape_string($con, $_POST['user_contact']));

    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                showAlert("Invalid email format.", "Error", "../media/error-pic.svg", "danger");
            });
        </script>';
        $show_signup_tab = true; // Keep signup tab active
    } elseif ($password !== $password_confirm) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                showAlert("Passwords do not match!", "Error", "../media/error-pic.svg", "danger");
            });
        </script>';
        $show_signup_tab = true;
    } else {
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $default_image = "user_images/testimage1.jpg";
        $user_ip = getIPAddress();

        // Check if username already exists in user_table or admin_table
        $select_query = "SELECT username FROM user_table WHERE username=? UNION SELECT username FROM admin_table WHERE username=?";
        $stmt = mysqli_prepare($con, $select_query);
        mysqli_stmt_bind_param($stmt, 'ss', $username, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $username_error = "Username already exists!";
            $show_signup_tab = true;
        } else {
            try {
                // Create user in Firebase Authentication
                $user = $auth->createUser([
                    'email' => $user_email,
                    'password' => $password,
                    'emailVerified' => false, // Ensure email is not verified initially
                ]);

                if ($user) {
                    // Send the email verification link using Firebase
                    $auth->sendEmailVerificationLink($user_email);

                    // Insert the new user into MySQL user_table
                    $insert_query = "INSERT INTO user_table (username, password, full_name, user_email, user_contact, user_image, user_ip) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $insert_query);
                    mysqli_stmt_bind_param($stmt, 'sssssss', $username, $hash_password, $full_name, $user_email, $user_contact, $default_image, $user_ip);

                    if (mysqli_stmt_execute($stmt)) {
                        $registration_status = "Registration successful! Please check your email for the verification link.";
                        echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                showAlert("'.$registration_status.'", "Success", "../media/emailsent.svg", "success");
                                setTimeout(function() {
                                    window.location.href = window.location.href.split("?")[0];
                                }, 9000);
                            });
                        </script>';
                    } else {
                        echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                showAlert("Registration error, please try again!", "Error", "../media/error-pic.svg", "danger");
                            });
                        </script>';
                        $show_signup_tab = true;
                    }
                }
            } catch (\Kreait\Firebase\Exception\Auth\EmailAlreadyExists $e) {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        showAlert("This email is already registered with Firebase! Please use a different email address.", "Error", "../media/error-pic.svg", "danger");
                    });
                </script>';
                $show_signup_tab = true;
            } catch (\Throwable $e) {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        showAlert("Error registering user: ' . $e->getMessage() . '", "Error", "../media/error-pic.svg", "danger");
                    });
                </script>';
                $show_signup_tab = true;
            }
        }
    }
}
// ---------------------
// ADMIN REGISTRATION
// ---------------------
$admin_contact = ""; // Initialize admin_contact variable
if (isset($_POST['admin_register'])) {
    if (!$isSuperAdmin) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                showAlert("Unauthorized access!", "Error", "../media/error-pic.svg", "danger");
            });
        </script>';
        exit;
    }

    $admin_username = trim(mysqli_real_escape_string($con, $_POST['admin_username']));
    $admin_email = trim(mysqli_real_escape_string($con, $_POST['admin_email']));
    $admin_password = trim(mysqli_real_escape_string($con, $_POST['admin_password']));
    $admin_password_confirm = trim(mysqli_real_escape_string($con, $_POST['admin_password_confirm']));
    $admin_full_name = trim(mysqli_real_escape_string($con, $_POST['admin_full_name']));
    $admin_contact = trim(mysqli_real_escape_string($con, $_POST['admin_contact']));
    $role = trim(mysqli_real_escape_string($con, $_POST['role']));

    if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                showAlert("Invalid email format.", "Error", "../media/error-pic.svg", "danger");
            });
        </script>';
    } elseif ($admin_password !== $admin_password_confirm) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                showAlert("Passwords do not match!", "Error", "../media/error-pic.svg", "danger");
            });
        </script>';
    } else {
        $hash_password = password_hash($admin_password, PASSWORD_DEFAULT);
        $admin_ip = getIPAddress();
        $default_admin_image = "user_images/testsimage1.jpg";

        // Check if the admin username already exists in user_table or admin_table
        $select_query = "SELECT username FROM user_table WHERE username=? UNION SELECT username FROM admin_table WHERE username=?";
        $stmt = mysqli_prepare($con, $select_query);
        mysqli_stmt_bind_param($stmt, 'ss', $admin_username, $admin_username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    showAlert("Admin username already exists!", "Error", "../media/error-pic.svg", "danger");
                });
            </script>';
        } else {
            try {
                // Create admin in Firebase Authentication
                $admin = $auth->createUser([
                    'email' => $admin_email,
                    'password' => $admin_password,
                    'emailVerified' => false, // Ensure email is not verified initially
                ]);

                if ($admin) {
                    // Send the email verification link using Firebase
                    $auth->sendEmailVerificationLink($admin_email);

                    // Insert the new admin into MySQL admin_table
                    $insert_query = "INSERT INTO `admin_table` (username, password, full_name, admin_email, admin_image, admin_contact, admin_ip, role) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $insert_query);
                    mysqli_stmt_bind_param($stmt, 'ssssssss', $admin_username, $hash_password, $admin_full_name, $admin_email, $default_admin_image, $admin_contact, $admin_ip, $role);

                    if (mysqli_stmt_execute($stmt)) {
                        echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                showAlert("Admin registration successful! Please check your email for the verification link.", "Success", "../media/emailsent.svg", "success");
                            });
                        </script>';
                    } else {
                        echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                showAlert("Admin registration error, please try again!", "Error", "../media/error-pic.svg", "danger");
                            });
                        </script>';
                    }
                }
            } catch (\Kreait\Firebase\Exception\Auth\EmailAlreadyExists $e) {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        showAlert("This email is already registered with Firebase! Please use a different email address.", "Error", "../media/error-pic.svg", "danger");
                    });
                </script>';
            } catch (\Throwable $e) {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        showAlert("Error registering admin: ' . $e->getMessage() . '", "Error", "../media/error-pic.svg", "danger");
                    });
                </script>';
            }
        }
    }
}

// ---------------------
// LOGIN PROCESSING
// ---------------------
if (isset($_POST['login'])) {
    $username = trim(mysqli_real_escape_string($con, $_POST['username']));
    $password = trim(mysqli_real_escape_string($con, $_POST['password']));

    // Check in admin_table
    $select_query_admin = "SELECT * FROM `admin_table` WHERE username=?";
    $stmt_admin = mysqli_prepare($con, $select_query_admin);
    mysqli_stmt_bind_param($stmt_admin, 's', $username);
    mysqli_stmt_execute($stmt_admin);
    $result_admin = mysqli_stmt_get_result($stmt_admin);

    if ($result_admin && mysqli_num_rows($result_admin) > 0) {
        $row = mysqli_fetch_assoc($result_admin);
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_username'] = $row['username'];
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_role'] = $row['role'];
            echo "<script>window.location.href = '../admin/adminpanel.php';</script>";
        } else {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    showAlert("Invalid password!", "Error", "../media/error-pic.svg", "danger");
                });
            </script>';
        }
    } else {
        // Check in user_table
        $select_query_user = "SELECT * FROM `user_table` WHERE username=?";
        $stmt_user = mysqli_prepare($con, $select_query_user);
        mysqli_stmt_bind_param($stmt_user, 's', $username);
        mysqli_stmt_execute($stmt_user);
        $result_user = mysqli_stmt_get_result($stmt_user);

        if ($result_user && mysqli_num_rows($result_user) > 0) {
            $row = mysqli_fetch_assoc($result_user);
            if (password_verify($password, $row['password'])) {
                try {
                    $user = $auth->getUserByEmail($row['user_email']);
                    if ($user->emailVerified) {
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['user_address'] = $row['user_address'];
                        $_SESSION['user_email'] = $row['user_email'];

                        // Update the last_login field
                        $update_query = "UPDATE `user_table` SET last_login = NOW() WHERE username=?";
                        $stmt_update = mysqli_prepare($con, $update_query);
                        mysqli_stmt_bind_param($stmt_update, 's', $username);
                        mysqli_stmt_execute($stmt_update);

                        echo "<script>window.location.href = '../home.php';</script>";
                    } else {
                        echo '<script>
                            document.addEventListener("DOMContentLoaded", function() {
                                showAlertWithResend("Please verify your email before logging in. Check your email for the verification link", "Verification Required", "../media/check-inbox.svg", "warning", "'.$row['user_email'].'");
                            });
                        </script>';
                    }
                } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                    echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            showAlert("Error fetching user from Firebase.", "Error", "../media/error-pic.svg", "danger");
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        showAlert("Invalid password!", "Error", "../media/error-pic.svg", "danger");
                    });
                </script>';
            }
        } else {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    showAlert("Username not found!", "Error", "../media/error-pic.svg", "danger");
                });
            </script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In / Sign Up</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <div class="auth-container">
    <div class="header-container">
      <img src="../media/rswoodlogo.png" alt="Your Company Logo" class="form-logo" width="500" />
      <h1 class="form-header">From Our Workshop to Your Home – Discover Unique Furniture</h1>
    </div>
    <div class="auth-form">
      <ul class="nav nav-tabs" id="authTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link <?php echo (!$show_signup_tab) ? 'active' : ''; ?>" id="login-tab" data-bs-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($show_signup_tab) ? 'active' : ''; ?>" id="signup-tab" data-bs-toggle="tab" href="#signup" role="tab" aria-controls="signup" aria-selected="false">Sign Up</a>
        </li>
        <?php if ($isSuperAdmin) { ?>
        <li class="nav-item">
          <a class="nav-link" id="admin-signup-tab" data-bs-toggle="tab" href="#admin_signup" role="tab" aria-controls="admin_signup" aria-selected="false">Admin Sign Up</a>
        </li>
        <?php } ?>
      </ul>
      <div class="tab-content" id="authTabContent">
        <!-- Login Form -->
        <div class="tab-pane fade <?php echo (!$show_signup_tab) ? 'show active' : ''; ?>" id="login" role="tabpanel" aria-labelledby="login-tab">
          <form method="POST" action="" id="loginForm">
            <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
            <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
          </form>
          <p>Don't have an account? <a href="#signup" data-bs-toggle="tab" aria-controls="signup">Sign up</a></p>
        </div>

        <!-- User Sign Up Form -->
        <div class="tab-pane fade <?php echo ($show_signup_tab) ? 'show active' : ''; ?>" id="signup" role="tabpanel" aria-labelledby="signup-tab">
          <form method="POST" action="" id="signupForm">
            <input type="text" class="form-control" name="username" placeholder="Choose a username (e.g., earl2001)" value="<?= htmlspecialchars($username) ?>" required>
            <?php if (!empty($username_error)) { ?>
              <div class="error-message"><?= $username_error ?></div>
            <?php } ?>
            <input type="email" class="form-control" name="user_email" placeholder="Enter your email address (e.g., johndoe@example.com)" value="<?= htmlspecialchars($user_email) ?>" required>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password (8+ characters)" required>
            <input type="password" class="form-control" name="password_confirm" id="confirm_password" placeholder="Confirm your password" required>
            <span id="password-error" class="error-message" style="display: none;">Passwords do not match.</span>
            <script>
              function checkPasswordMatch() {
                  var password = document.getElementById('password').value;
                  var confirmPassword = document.getElementById('confirm_password').value;
                  var errorElement = document.getElementById('password-error');
                  var submitButton = document.querySelector('[name="user_register"]');

                  if (password !== confirmPassword) {
                      errorElement.style.display = 'block';
                      submitButton.disabled = true;
                  } else {
                      errorElement.style.display = 'none';
                      submitButton.disabled = false;
                  }
              }
              document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);
            </script>
            <input type="text" class="form-control" name="full_name" placeholder="Enter your full name (e.g., Jason Manjares)" value="<?= htmlspecialchars($full_name) ?>" required>
            <input type="text" class="form-control" name="user_contact" placeholder="Enter your contact number (e.g., +639923071369)" value="<?= htmlspecialchars($user_contact) ?>" required pattern="\d{11}" maxlength="11" title="Please enter exactly 11 digits" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            <button type="submit" name="user_register" class="btn btn-success">Sign Up</button>
          </form>
          <p>Already have an account? <a href="#login" data-bs-toggle="tab" aria-controls="login">Log In</a></p>
        </div>

        <!-- Admin Sign Up Form (visible only for super_admin) -->
        <?php if ($isSuperAdmin) { ?>
        <div class="tab-pane fade" id="admin_signup" role="tabpanel" aria-labelledby="admin-signup-tab">
          <form method="POST" action="" id="adminSignupForm">
            <input type="text" class="form-control" name="admin_username" placeholder="Choose admin username (e.g., admin123)" required>
            <input type="email" class="form-control" name="admin_email" placeholder="Enter admin email (e.g., admin@example.com)" required>
            <input type="password" class="form-control" name="admin_password" id="admin_password" placeholder="Password (8+ characters)" required>
            <input type="password" class="form-control" name="admin_password_confirm" id="admin_confirm_password" placeholder="Confirm your password" required>
            <span id="admin-password-error" class="error-message" style="display: none;">Passwords do not match.</span>
            <script>
              function checkAdminPasswordMatch() {
                  var password = document.getElementById('admin_password').value;
                  var confirmPassword = document.getElementById('admin_confirm_password').value;
                  var errorElement = document.getElementById('admin-password-error');
                  var submitButton = document.querySelector('[name="admin_register"]');

                  if (password !== confirmPassword) {
                      errorElement.style.display = 'block';
                      submitButton.disabled = true;
                  } else {
                      errorElement.style.display = 'none';
                      submitButton.disabled = false;
                  }
              }
              document.getElementById('admin_confirm_password').addEventListener('input', checkAdminPasswordMatch);
            </script>
            <input type="text" class="form-control" name="admin_full_name" placeholder="Enter your full name (e.g., Admin User)" required>
            <input type="text" class="form-control" name="admin_contact" placeholder="Enter your contact number (e.g., +639923071369)" value="<?= htmlspecialchars($admin_contact) ?>" required pattern="\d{11}" maxlength="11" title="Please enter exactly 11 digits" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            <select class="form-control" name="role" required>
              <option value="">Select Role</option>
              <option value="super_admin">Super Admin</option>
              <option value="manager">Manager</option>
              <option value="editor">Editor</option>
            </select>
            <button type="submit" name="admin_register" class="btn btn-success">Register Admin</button>
          </form>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function showAlert(message, title, image, type) {
        var alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show d-flex flex-column align-items-center text-center" role="alert" style="padding: 1rem; border-radius: 0.5rem; margin: 1rem 0;">
            <img src="${image}" alt="${title}" style="width: 80px; height: 80px; margin-bottom: 10px;">
            <div>
                <strong>${title}:</strong> ${message}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-top: 10px;"></button>
        </div>`;
        document.body.insertAdjacentHTML('afterbegin', alertHtml);
    }

    function showAlertWithResend(message, title, image, type, email) {
        var alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show d-flex flex-column align-items-center text-center" role="alert" style="padding: 1rem; border-radius: 0.5rem; margin: 1rem 0;">
            <img src="${image}" alt="${title}" style="width: 60px; height: 60px; margin-bottom: 8px;">
            <div>
                <strong>${title}:</strong> ${message}
                <button id="resendButton" class="btn btn-warning btn-sm" style="margin-top: 5px;">Resend Verification Link</button>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="margin-top: 10px;"></button>
        </div>`;
        document.body.insertAdjacentHTML('afterbegin', alertHtml);

        document.getElementById('resendButton').addEventListener('click', function() {
            resendVerificationLink(email);
            this.disabled = true;
            setTimeout(() => {
                this.disabled = false;
            }, 120000); // 2 minutes
        });
    }

    function resendVerificationLink(email) {
        fetch('resend_verification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert("Verification link sent successfully!", "Success", "../media/agree.svg", "success");
            } else {
                showAlert("Failed to send verification link. Please try again later.", "Error", "../media/error-pic.svg", "danger");
            }
        })
        .catch(error => {
            showAlert("An error occurred. Please try again later.", "Error", "../media/error-pic.svg", "danger");
        });
    }
  </script>
</body>
</html>

<!-- Optional: Firebase SDK Initialization -->
<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
  import { getAuth } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-auth.js";
  const firebaseConfig = {
      apiKey: "",
      authDomain: "",
      projectId: "",
      storageBucket: "",
      messagingSenderId: "",
      appId: "",
      measurementId: ""
  };
  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  
</script>

</body>
</html>
