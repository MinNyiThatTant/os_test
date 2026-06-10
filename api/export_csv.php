<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../admin/login.html");
    exit();
}
require_once '../includes/db_connect.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="exam_results_' . date('Y-m-d') . '.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ['Student Name', 'Roll No', 'Test Name', 'Score', 'Total Questions', 'Percentage', 'Date']);

$sql = "SELECT s.fullname, s.roll_no, c.name as test_name, a.score, a.total_questions, a.attempted_at 
        FROM student_attempts a 
        JOIN students s ON a.student_id = s.id 
        JOIN categories c ON a.category_id = c.id 
        ORDER BY a.attempted_at DESC";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    $percent = round(($row['score'] / $row['total_questions']) * 100, 1);
    fputcsv($output, [
        $row['fullname'],
        $row['roll_no'],
        $row['test_name'],
        $row['score'],
        $row['total_questions'],
        $percent . '%',
        $row['attempted_at']
    ]);
}
fclose($output);
?>