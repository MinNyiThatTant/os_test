<?php
session_start();
define('SITE_NAME', 'OS Exam System');
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', password_hash('admin123', PASSWORD_DEFAULT));
define('EXAM_DURATION', 900); // 15 minutes in seconds
?>