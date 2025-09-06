<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

header('Location: admin_login.php');