<?php
include 'db_connect.php';
$data = json_decode(file_get_contents("php://input"), true);
$q = $data['question'];
$a = $data['a'];
$b = $data['b'];
$c = $data['c'];
$d = $data['d'];
$correct = $data['correct'];
$conn->query("INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES ('$q','$a','$b','$c','$d','$correct')");
echo "ok";
?>