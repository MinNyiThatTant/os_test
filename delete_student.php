<?php
include 'db_connect.php';
$id = $_POST['id'];
$conn->query("DELETE FROM results WHERE student_id=$id");
$conn->query("DELETE FROM students WHERE id=$id");
echo "deleted";
?>