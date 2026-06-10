<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.html");
    exit();
}
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-tabs { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .tab-btn { background: #2c3e66; padding: 10px 20px; border: none; border-radius: 30px; cursor: pointer; color: white; }
        .tab-btn.active { background: #00b894; }
        .tab-content { display: none; background: rgba(255,255,255,0.1); padding: 20px; border-radius: 20px; margin-top: 10px; }
        .tab-content.active { display: block; }
        table { width: 100%; border-collapse: collapse; color: white; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        .delete-btn, .edit-btn, .del-btn { padding: 5px 10px; margin: 2px; border: none; border-radius: 5px; cursor: pointer; }
        .delete-btn { background: #e74c3c; color: white; }
        .edit-btn { background: #f39c12; color: white; }
        .del-btn { background: #c0392b; }
        .stats-box { display: inline-block; background: #2c3e66; padding: 15px; margin: 10px; border-radius: 15px; min-width: 150px; }
    </style>
</head>
<body>
<div class="container" style="max-width: 1200px;">
    <h2>OS_Test Admin Dashboard</h2>
    <div style="text-align: right;"><a href="admin_logout.php" style="color: #ff9999;">Logout</a></div>

    <div class="admin-tabs">
        <button class="tab-btn active" onclick="showTab('stats')">Statistics</button>
        <button class="tab-btn" onclick="showTab('students')">Students</button>
        <button class="tab-btn" onclick="showTab('questions')">Questions</button>
        <button class="tab-btn" onclick="showTab('results')">Results</button>
    </div>

    <!-- Tab 1: Statistics -->
    <div id="tab-stats" class="tab-content active">
        <?php
        $total_students = $conn->query("SELECT COUNT(*) as c FROM students")->fetch_assoc()['c'];
        $total_questions = $conn->query("SELECT COUNT(*) as c FROM questions")->fetch_assoc()['c'];
        $total_exams = $conn->query("SELECT COUNT(*) as c FROM results")->fetch_assoc()['c'];
        $avg_score = $conn->query("SELECT AVG(score) as a FROM results")->fetch_assoc()['a'];
        ?>
        <div class="stats-box">Students: <?php echo $total_students; ?></div>
        <div class="stats-box">Questions: <?php echo $total_questions; ?></div>
        <div class="stats-box">TestsTaken: <?php echo $total_exams; ?></div>
        <div class="stats-box">Avg Score: <?php echo round($avg_score, 2); ?></div>
    </div>

    <!-- Tab 2: Students Management -->
    <div id="tab-students" class="tab-content">
        <h3>Registered Students</h3>
        <div id="studentsList"></div>
    </div>

    <!-- Tab 3: Questions Management -->
    <div id="tab-questions" class="tab-content">
        <h3>Add New Question</h3>
        <input type="text" id="qtext" placeholder="Question" style="width:100%;"><br>
        <input type="text" id="optA" placeholder="Option A"><br>
        <input type="text" id="optB" placeholder="Option B"><br>
        <input type="text" id="optC" placeholder="Option C"><br>
        <input type="text" id="optD" placeholder="Option D"><br>
        <select id="correct">
            <option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>
        </select>
        <button onclick="addQuestion()">Add Question</button>
        
        <h3>Existing Questions</h3>
        <div id="questionsList"></div>
    </div>

    <!-- Tab 4: Results Management -->
    <div id="tab-results" class="tab-content">
        <button onclick="exportCSV()">Export Results as CSV</button>
        <div id="resultsTable"></div>
    </div>
</div>

<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.getElementById(`tab-${tab}`).classList.add('active');
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    if(tab === 'students') loadStudents();
    if(tab === 'questions') loadQuestions();
    if(tab === 'results') loadResults();
}

function loadStudents() {
    fetch("get_students.php").then(res => res.json()).then(data => {
        let html = "<table><tr><th>ID</th><th>Full Name</th><th>Roll No</th><th>Action</th></tr>";
        data.forEach(s => {
            html += `<tr>
                        <td>${s.id}</td>
                        <td>${s.fullname}</td>
                        <td>${s.roll_no}</td>
                        <td><button class="delete-btn" onclick="deleteStudent(${s.id})">Delete</button></td>
                     </tr>`;
        });
        html += "</table>";
        document.getElementById("studentsList").innerHTML = html;
    });
}

function deleteStudent(id) {
    if(confirm("Delete this student and all results?")) {
        fetch("delete_student.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `id=${id}`
        }).then(() => loadStudents());
    }
}

function loadQuestions() {
    fetch("get_questions_admin.php").then(res => res.json()).then(data => {
        let html = "<table><tr><th>ID</th><th>Question</th><th>Options</th><th>Correct</th><th>Actions</th></tr>";
        data.forEach(q => {
            html += `<tr>
                        <td>${q.id}</td>
                        <td>${q.question_text}</td>
                        <td>A:${q.option_a}<br>B:${q.option_b}<br>C:${q.option_c}<br>D:${q.option_d}</td>
                        <td>${q.correct_answer}</td>
                        <td>
                            <button class="edit-btn" onclick="editQuestion(${q.id})">Edit</button>
                            <button class="del-btn" onclick="deleteQuestion(${q.id})">Delete</button>
                        </td>
                     </tr>`;
        });
        html += "</table>";
        document.getElementById("questionsList").innerHTML = html;
    });
}

function addQuestion() {
    let data = {
        question: document.getElementById("qtext").value,
        a: document.getElementById("optA").value,
        b: document.getElementById("optB").value,
        c: document.getElementById("optC").value,
        d: document.getElementById("optD").value,
        correct: document.getElementById("correct").value
    };
    fetch("add_question.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(data)
    }).then(() => {
        alert("Question added");
        document.getElementById("qtext").value = "";
        document.getElementById("optA").value = "";
        document.getElementById("optB").value = "";
        document.getElementById("optC").value = "";
        document.getElementById("optD").value = "";
        loadQuestions();
    });
}

function deleteQuestion(id) {
    if(confirm("Delete question?")) {
        fetch("delete_question.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `id=${id}`
        }).then(() => loadQuestions());
    }
}

function editQuestion(id) {
    let newText = prompt("Enter new question text:");
    if(newText) {
        fetch("update_question.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `id=${id}&question=${encodeURIComponent(newText)}`
        }).then(() => loadQuestions());
    }
}

function loadResults() {
    fetch("get_results.php").then(res => res.json()).then(data => {
        let html = "<table><tr><th>Name</th><th>Roll No</th><th>Score</th><th>Total</th><th>Date</th></tr>";
        data.forEach(r => {
            html += `<tr>
                        <td>${r.fullname}</td>
                        <td>${r.roll_no}</td>
                        <td>${r.score}</td>
                        <td>${r.total_questions}</td>
                        <td>${r.submitted_at}</td>
                     </tr>`;
        });
        html += "</table>";
        document.getElementById("resultsTable").innerHTML = html;
    });
}

function exportCSV() {
    window.location.href = "export_results_csv.php";
}

// Default load when page opens
loadStudents();
loadQuestions();
loadResults();
</script>
</body>
</html>