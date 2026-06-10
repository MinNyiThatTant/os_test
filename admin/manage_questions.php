<?php
require_once '../includes/db_connect.php';
require_once 'auth.php';
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$category = $conn->query("SELECT * FROM categories WHERE id=$category_id")->fetch_assoc();
if(!$category) {
    echo "<script>alert('Category not found'); window.location='manage_categories.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Questions - <?php echo htmlspecialchars($category['name']); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .question-row { background: rgba(255,255,255,0.05); padding: 15px; margin: 10px 0; border-radius: 10px; }
    </style>
</head>
<body>
<div class="container" style="max-width: 1000px;">
    <h2>Questions for: <?php echo htmlspecialchars($category['name']); ?></h2>
    <a href="manage_categories.php">← Back to Tests</a>
    
    <div style="margin: 20px 0; background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px;">
        <h3>Add Question</h3>
        <input type="text" id="qtext" placeholder="Question" style="width:100%; margin:5px 0; padding:10px;"><br>
        <input type="text" id="optA" placeholder="Option A" style="width:48%; margin:5px 1%; padding:8px;">
        <input type="text" id="optB" placeholder="Option B" style="width:48%; margin:5px 1%; padding:8px;"><br>
        <input type="text" id="optC" placeholder="Option C" style="width:48%; margin:5px 1%; padding:8px;">
        <input type="text" id="optD" placeholder="Option D" style="width:48%; margin:5px 1%; padding:8px;"><br>
        <select id="correct" style="padding:8px; margin:5px;">
            <option value="A">Correct: A</option>
            <option value="B">Correct: B</option>
            <option value="C">Correct: C</option>
            <option value="D">Correct: D</option>
        </select>
        <button onclick="addQuestion()" style="background:#00b894; padding:8px 20px;">Add Question</button>
    </div>
    
    <h3>Existing Questions</h3>
    <div id="questionsList"></div>
</div>

<script>
let categoryId = <?php echo $category_id; ?>;

function loadQuestions() {
    fetch(`../api/get_questions.php?category_id=${categoryId}`)
        .then(res => res.json())
        .then(data => {
            let html = "";
            data.forEach((q, idx) => {
                html += `
                    <div class="question-row">
                        <strong>${idx+1}. ${q.question_text}</strong><br>
                        <small>A: ${q.option_a} | B: ${q.option_b} | C: ${q.option_c} | D: ${q.option_d}</small><br>
                        <small>Correct: ${q.correct_answer}</small><br>
                        <button onclick="deleteQuestion(${q.id})" style="background:#e74c3c; padding:3px 10px; margin:5px;">Delete</button>
                        <button onclick="editQuestion(${q.id})" style="background:#f39c12; padding:3px 10px; margin:5px;">Edit Text</button>
                    </div>
                `;
            });
            document.getElementById("questionsList").innerHTML = html || "<p>No questions yet. Add one above!</p>";
        });
}

function addQuestion() {
    let data = {
        category_id: categoryId,
        question: document.getElementById("qtext").value,
        a: document.getElementById("optA").value,
        b: document.getElementById("optB").value,
        c: document.getElementById("optC").value,
        d: document.getElementById("optD").value,
        correct: document.getElementById("correct").value
    };
    if(!data.question) { alert("Please enter question"); return; }
    fetch("../api/add_question.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(data)
    }).then(() => {
        document.getElementById("qtext").value = "";
        document.getElementById("optA").value = "";
        document.getElementById("optB").value = "";
        document.getElementById("optC").value = "";
        document.getElementById("optD").value = "";
        loadQuestions();
    });
}

function deleteQuestion(id) {
    if(confirm("Delete this question?")) {
        fetch("../api/delete_question.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `id=${id}`
        }).then(() => loadQuestions());
    }
}

function editQuestion(id) {
    let newText = prompt("Enter new question text:");
    if(newText) {
        fetch("../api/update_question.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `id=${id}&question=${encodeURIComponent(newText)}`
        }).then(() => loadQuestions());
    }
}

loadQuestions();
</script>
</body>
</html>