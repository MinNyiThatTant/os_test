<?php
session_start();
header('Content-Type: text/plain');

// Admin credentials
$admin_username = "admin";
$admin_password = "admin123";  // plaintext

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Debug log (check Apache error log)
error_log("Admin login attempt: username=$username");

if($username === $admin_username && $password === $admin_password) {
    $_SESSION['admin_logged_in'] = true;
    echo "success";
} else {
    echo "fail";
}
?>