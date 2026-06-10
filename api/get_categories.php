<?php
require_once '../includes/db_connect.php';
$result = $conn->query("
    SELECT c.*, COUNT(q.id) as question_count 
    FROM categories c 
    LEFT JOIN questions q ON q.category_id = c.id 
    GROUP BY c.id
");
$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>