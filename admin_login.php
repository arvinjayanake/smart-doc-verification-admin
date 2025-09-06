<?php
require_once 'db/admin_controller.php';

$login_error = null;

// Process login if form submitted at the very top
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $admin = AdminController::login($email, $password);

    if ($admin) {
        // Start session and store admin data
        session_start();
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];

        // Redirect to admin dashboard
        header('Location: manage_usage.php');
        exit();
    } else {
        $login_error = "Invalid email or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/login_style.css"/>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="brand">
            <div class="brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <div class="brand-text">Admin Portal</div>
        </div>

        <h1 class="login-title">Sign in to your account</h1>



        <form method="POST" action="admin_login.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn login-btn">Sign in</button>

            <?php
            if ($login_error != null){
                echo '<div class="alerts">
                        <div class="alert err">
                            Invalid email or password. Please try again.
                        </div>
                    </div>';
            }

            ?>
        </form>

        <div class="login-footer">
            &copy; <?php echo date('Y'); ?> Arvin Jayanake [CL/BSCSD/29/01].
        </div>
    </div>
</div>
</body>
</html>