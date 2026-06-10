<?php
require_once '../includes/db_connect.php';
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .delete-btn { background: #e74c3c; padding: 5px 10px; border: none; border-radius: 5px; color: white; cursor: pointer; }
    </style>
</head>
<body>
<div class="container" style="max-width: 1000px;">
    <h2>Manage Students</h2>
    <a href="dashboard.php">← Back to Dashboard</a>
    <div id="studentsList" style="margin-top: 20px;"></div>
</div>

<script>
function loadStudents() {
    fetch('../api/get_students.php')
        .then(res => res.json())
        .then(data => {
            let html = "<table><th>ID</th><th>Full Name</th><th>Roll Number</th><th>Registered Date</th><th>Action</th><tr>";
            data.forEach(s => {
                html += `<tr>
                            <td>${s.id}</td>
                            <td>${s.fullname}</td>
                            <td>${s.roll_no}</td>
                            <td>${s.created_at || '-'}</td>
                            <td><button class="delete-btn" onclick="deleteStudent(${s.id})">Delete</button></td>
                         </tr>`;
            });
            html += "</table>";
            document.getElementById("studentsList").innerHTML = html;
        });
}

function deleteStudent(id) {
    if(confirm("Delete this student and all their exam attempts?")) {
        fetch("../api/delete_student.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `id=${id}`
        }).then(() => loadStudents());
    }
}

loadStudents();
</script>
</body>
</html>