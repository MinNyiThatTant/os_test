<?php
include 'db_connect.php';
session_start();
$roll = $_POST['roll_no'];
$pass = $_POST['password'];

$sql = "SELECT * FROM students WHERE roll_no='$roll'";
$result = $conn->query($sql);
if($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if(password_verify($pass, $row['password'])) {
        $_SESSION['student_id'] = $row['id'];
        echo "success";
    } else echo "fail";
} else echo "fail";
?>