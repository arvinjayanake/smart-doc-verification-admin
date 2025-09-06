<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$admin_id = $_SESSION['admin_id'] ?? null;
$admin_name = $_SESSION['admin_name'] ?? null;
$admin_email = $_SESSION['admin_email'] ?? null;

if ($admin_id == null || $admin_name == null || $admin_email ==null){
    header('Location: admin_login.php');
}