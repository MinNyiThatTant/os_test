<?php
include 'db_connect.php';
$data = json_decode(file_get_contents("php://input"), true);
$roll = $data['roll'];
$answers = $data['answers'];

$student = $conn->query("SELECT id FROM students WHERE roll_no='$roll'")->fetch_assoc();
$student_id = $student['id'];

$total = 0;
$score = 0;
foreach($answers as $ans) {
    $qid = $ans['qid'];
    $selected = $ans['ans'];
    $correct = $conn->query("SELECT correct_answer FROM questions WHERE id=$qid")->fetch_assoc();
    if($correct['correct_answer'] == $selected) $score++;
    $total++;
}
$conn->query("INSERT INTO results (student_id, score, total_questions) VALUES ($student_id, $score, $total)");
echo "$score / $total";
?>