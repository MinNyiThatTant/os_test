<?php
session_start();
require_once '../includes/db_connect.php';

// Check if student is logged in
if(!isset($_SESSION['student_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$student_id = $_SESSION['student_id'];
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// If category_id is provided, check specific test
if($category_id > 0) {
    $result = $conn->query("
        SELECT * FROM student_attempts 
        WHERE student_id = $student_id AND category_id = $category_id
    ");
    
    if($result->num_rows > 0) {
        $attempt = $result->fetch_assoc();
        echo json_encode([
            'attempted' => true,
            'score' => $attempt['score'],
            'total' => $attempt['total_questions'],
            'attempted_at' => $attempt['attempted_at']
        ]);
    } else {
        echo json_encode(['attempted' => false]);
    }
}
// If no category_id, return all attempted tests
else {
    $result = $conn->query("
        SELECT c.id, c.name, a.score, a.total_questions, a.attempted_at
        FROM student_attempts a
        JOIN categories c ON a.category_id = c.id
        WHERE a.student_id = $student_id
    ");
    
    $attempts = [];
    while($row = $result->fetch_assoc()) {
        $attempts[] = $row;
    }
    echo json_encode($attempts);
}
?>