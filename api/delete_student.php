<?php
require_once '../includes/db_connect.php';
$id = (int)$_POST['id'];
$conn->query("DELETE FROM student_attempts WHERE student_id=$id");
$conn->query("DELETE FROM students WHERE id=$id");
echo "deleted";
?>