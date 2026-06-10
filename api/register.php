<?php
require_once '../includes/db_connect.php';

$name = $_POST['fullname'];
$roll = $_POST['roll_no'];
$pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

$check = $conn->query("SELECT * FROM students WHERE roll_no='$roll'");
if($check->num_rows > 0) {
    echo "Roll number already exists";
} else {
    $conn->query("INSERT INTO students (fullname, roll_no, password) VALUES ('$name', '$roll', '$pass')");
    echo "Registered successfully";
}
?>