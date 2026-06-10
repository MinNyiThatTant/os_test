<?php
session_start();
require_once '../includes/db_connect.php';

$roll = $_POST['roll_no'];
$pass = $_POST['password'];

$sql = "SELECT * FROM students WHERE roll_no='$roll'";
$result = $conn->query($sql);
if($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if(password_verify($pass, $row['password'])) {
        $_SESSION['student_id'] = $row['id'];
        $_SESSION['student_roll'] = $row['roll_no'];
        $_SESSION['student_name'] = $row['fullname'];
        echo "success";
    } else echo "fail";
} else echo "fail";
?>