<?php
include 'db_connect.php';
$result = $conn->query("SELECT id, fullname, roll_no FROM students");
$students = [];
while($row = $result->fetch_assoc()) $students[] = $row;
echo json_encode($students);
?>