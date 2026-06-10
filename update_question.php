<?php
include 'db_connect.php';
$id = $_POST['id'];
$question = $conn->real_escape_string($_POST['question']);
$conn->query("UPDATE questions SET question_text='$question' WHERE id=$id");
echo "updated";
?>