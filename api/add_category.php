<?php
require_once '../includes/db_connect.php';
$data = json_decode(file_get_contents("php://input"), true);
$name = $conn->real_escape_string($data['name']);
$desc = $conn->real_escape_string($data['description']);
$conn->query("INSERT INTO categories (name, description) VALUES ('$name', '$desc')");
echo "ok";
?>