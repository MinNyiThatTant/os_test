<?php
require_once '../includes/db_connect.php';
$data = json_decode(file_get_contents("php://input"), true);
$student_id = $data['student_id'];
$category_id = $data['category_id'];
$answers = $data['answers'];

// Check if already attempted
$check = $conn->query("SELECT * FROM student_attempts WHERE student_id=$student_id AND category_id=$category_id");
if($check->num_rows > 0) {
    echo "Already attempted!";
    exit();
}

$score = 0;
$total = 0;
foreach($answers as $ans) {
    $qid = (int)$ans['qid'];
    $selected = $ans['ans'];
    $correct = $conn->query("SELECT correct_answer FROM questions WHERE id=$qid")->fetch_assoc();
    if($correct && $correct['correct_answer'] == $selected) {
        $score++;
    }
    $total++;
}

$conn->query("INSERT INTO student_attempts (student_id, category_id, score, total_questions) 
              VALUES ($student_id, $category_id, $score, $total)");
echo "$score / $total";
?>