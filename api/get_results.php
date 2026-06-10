<?php
require_once '../includes/db_connect.php';
$sql = "SELECT s.fullname, s.roll_no, c.name as test_name, a.score, a.total_questions, a.attempted_at 
        FROM student_attempts a 
        JOIN students s ON a.student_id = s.id 
        JOIN categories c ON a.category_id = c.id 
        ORDER BY a.attempted_at DESC";
$result = $conn->query($sql);
$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>