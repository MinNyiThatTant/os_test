<?php
include 'db_connect.php';
$id = $_POST['id'];
$conn->query("DELETE FROM questions WHERE id=$id");
echo "deleted";
?>