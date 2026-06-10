<?php
require_once '../includes/db_connect.php';
$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

$result = $conn->query("
    SELECT c.*, 
           COUNT(DISTINCT q.id) as question_count,
           a.score,
           a.total_questions,
           CASE WHEN a.id IS NOT NULL THEN 1 ELSE 0 END as completed
    FROM categories c
    LEFT JOIN questions q ON q.category_id = c.id
    LEFT JOIN student_attempts a ON a.category_id = c.id AND a.student_id = $student_id
    GROUP BY c.id
");
$tests = [];
while($row = $result->fetch_assoc()) {
    $tests[] = $row;
}
echo json_encode($tests);
?>