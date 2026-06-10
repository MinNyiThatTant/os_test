<?php
include 'db_connect.php';
$result = $conn->query("SELECT id, question_text, option_a, option_b, option_c, option_d FROM questions");
$questions = [];
while($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
echo json_encode($questions);
?>