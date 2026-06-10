<?php
require_once '../includes/db_connect.php';
$id = (int)$_POST['id'];
$name = $conn->real_escape_string($_POST['name']);
$desc = $conn->real_escape_string($_POST['description']);
$conn->query("UPDATE categories SET name='$name', description='$desc' WHERE id=$id");
echo "updated";
?>