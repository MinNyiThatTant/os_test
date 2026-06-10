<?php
require_once '../includes/db_connect.php';
$data = json_decode(file_get_contents("php://input"), true);
$cat_id = (int)$data['category_id'];
$q = $conn->real_escape_string($data['question']);
$a = $conn->real_escape_string($data['a']);
$b = $conn->real_escape_string($data['b']);
$c = $conn->real_escape_string($data['c']);
$d = $conn->real_escape_string($data['d']);
$correct = $data['correct'];
$conn->query("INSERT INTO questions (category_id, question_text, option_a, option_b, option_c, option_d, correct_answer) 
              VALUES ($cat_id, '$q', '$a', '$b', '$c', '$d', '$correct')");
echo "ok";
?>