<?php
require_once '../includes/db_connect.php';
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$result = $conn->query("SELECT id, question_text, option_a, option_b, option_c, option_d FROM questions WHERE category_id=$category_id");
$questions = [];
while($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
echo json_encode($questions);
?>