<?php
require_once '../includes/db_connect.php';
$result = $conn->query("SELECT id, fullname, roll_no, created_at FROM students ORDER BY id DESC");
$students = [];
while($row = $result->fetch_assoc()) {
    $students[] = $row;
}
echo json_encode($students);
?>