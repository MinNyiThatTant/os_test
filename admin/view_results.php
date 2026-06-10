<?php
require_once '../includes/db_connect.php';
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Results</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .export-btn { background: #3498db; padding: 10px 20px; border: none; border-radius: 25px; color: white; cursor: pointer; margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container" style="max-width: 1200px;">
    <h2>Test Results</h2>
    <a href="dashboard.php">← Back to Dashboard</a>
    <div style="margin: 20px 0;">
        <button class="export-btn" onclick="exportCSV()">📎 Export as CSV</button>
    </div>
    <div id="resultsTable"></div>
</div>

<script>
function loadResults() {
    fetch('../api/get_results.php')
        .then(res => res.json())
        .then(data => {
            let html = "<table><th>Student Name</th><th>Roll No</th><th>Test Name</th><th>Score</th><th>Total</th><th>Percentage</th><th>Date</th></tr>";
            data.forEach(r => {
                let percent = ((r.score / r.total_questions) * 100).toFixed(1);
                html += `<tr>
                            <td>${r.fullname}</td>
                            <td>${r.roll_no}</td>
                            <td>${r.test_name}</td>
                            <td>${r.score}</td>
                            <td>${r.total_questions}</td>
                            <td>${percent}%</td>
                            <td>${r.attempted_at}</td>
                         </tr>`;
            });
            html += "</table>";
            document.getElementById("resultsTable").innerHTML = html || "<p>No results yet.</p>";
        });
}

function exportCSV() {
    window.location.href = "../api/export_csv.php";
}

loadResults();
</script>
</body>
</html>