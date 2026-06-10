<?php
include 'db_connect.php';
$sql = "SELECT students.fullname, students.roll_no, results.score, results.total_questions FROM results JOIN students ON results.student_id = students.id";
$res = $conn->query($sql);
$data = [];
while($row = $res->fetch_assoc()) $data[] = $row;
echo json_encode($data);
?>