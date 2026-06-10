<?php
require_once '../includes/db_connect.php';
$id = (int)$_POST['id'];
$conn->query("DELETE FROM categories WHERE id=$id");
echo "deleted";
?>