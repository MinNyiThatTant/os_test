<?php
require_once '../includes/db_connect.php';
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard </title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
        .admin-card { background: rgba(255,255,255,0.1); padding: 25px; border-radius: 20px; text-align: center; cursor: pointer; transition: 0.3s; }
        .admin-card:hover { background: rgba(255,255,255,0.2); transform: translateY(-5px); }
        .admin-card h3 { margin-bottom: 10px; }
        .admin-card .count { font-size: 2.5rem; font-weight: bold; color: #00b894; }
    </style>
</head>
<body>
<div class="container" style="max-width: 1200px;">
    <h2>Admin Dashboard</h2>
    <div style="text-align: right;"><a href="logout.php" style="color: #ff9999;">Logout</a></div>
    
    <?php
    $total_students = $conn->query("SELECT COUNT(*) as c FROM students")->fetch_assoc()['c'];
    $total_categories = $conn->query("SELECT COUNT(*) as c FROM categories")->fetch_assoc()['c'];
    $total_questions = $conn->query("SELECT COUNT(*) as c FROM questions")->fetch_assoc()['c'];
    $total_attempts = $conn->query("SELECT COUNT(*) as c FROM student_attempts")->fetch_assoc()['c'];
    ?>
    
    <div class="admin-grid">
        <div class="admin-card" onclick="location.href='manage_categories.php'">
            <h3>Tests</h3>
            <div class="count"><?php echo $total_categories; ?></div>
            <small>Manage categories</small>
        </div>
        <div class="admin-card" onclick="location.href='manage_questions.php'">
            <h3>Questions</h3>
            <div class="count"><?php echo $total_questions; ?></div>
            <small>Manage questions</small>
        </div>
        <div class="admin-card" onclick="location.href='manage_students.php'">
            <h3>Students</h3>
            <div class="count"><?php echo $total_students; ?></div>
            <small>Manage students</small>
        </div>
        <div class="admin-card" onclick="location.href='view_results.php'">
            <h3>Results</h3>
            <div class="count"><?php echo $total_attempts; ?></div>
            <small>View exam results</small>
        </div>
    </div>
</div>
</body>
</html>