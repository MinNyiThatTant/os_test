<?php
session_start();
$user = $_POST['username'];
$pass = $_POST['password'];

// Change these credentials as you wish
$admin_user = "admin";
$admin_pass_hash = password_hash("admin123", PASSWORD_DEFAULT); // default password: admin123

if($user === $admin_user && password_verify($pass, $admin_pass_hash)) {
    $_SESSION['admin_logged_in'] = true;
    echo "success";
} else {
    echo "fail";
}
?>