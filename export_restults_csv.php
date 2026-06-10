<?php
include 'db_connect.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="exam_results.csv"');
$output = fopen("php://output", "w");
fputcsv($output, ['Name', 'Roll No', 'Score', 'Total', 'Date']);
$sql = "SELECT students.fullname, students.roll_no, results.score, results.total_questions, results.submitted_at 
        FROM results JOIN students ON results.student_id = students.id";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}
fclose($output);
?>