<?php
$host = "localhost:3307";
$user = "root";
$pass = "";
$db = "os_exam_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set timezone
date_default_timezone_set('Asia/Yangon');
?>