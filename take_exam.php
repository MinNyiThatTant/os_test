<?php
session_start();
if(!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}
$student_id = $_SESSION['student_id'];
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
require_once 'includes/db_connect.php';

$cat = $conn->query("SELECT * FROM categories WHERE id = $category_id")->fetch_assoc();

// Check if category exists
if(!$cat) {
    echo "<script>alert('Test not found!'); window.location='student_dashboard.php';</script>";
    exit();
}

// Check if already attempted
$check = $conn->query("SELECT * FROM student_attempts WHERE student_id=$student_id AND category_id=$category_id");
if($check->num_rows > 0) {
    $attempt = $check->fetch_assoc();
    echo "<script>
        alert('You have already taken this test!\\nYour score: {$attempt['score']}/{$attempt['total_questions']}');
        window.location='student_dashboard.php';
    </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($cat['name']); ?> - OS Test</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .timer { font-size: 1.5rem; background: #2c3e66; display: inline-block; padding: 10px 25px; border-radius: 40px; margin-bottom: 20px; }
        .question-card { background: rgba(255,255,255,0.1); padding: 20px; margin: 15px 0; border-radius: 15px; }
        .options label { display: block; margin: 10px 0; cursor: pointer; }
        .submit-btn { background: #00b894; padding: 15px 30px; font-size: 1.2rem; margin-top: 20px; }
        .error-msg { color: #ff9999; text-align: center; padding: 20px; }
    </style>
</head>
<body>
<div class="container" style="max-width: 900px;">
    <h2><?php echo htmlspecialchars($cat['name']); ?></h2>
    <p><?php echo htmlspecialchars($cat['description']); ?></p>
    <div class="timer"><span id="timer">15:00</span></div>
    <div id="questionsArea">
        <div class="error-msg">Loading questions...</div>
    </div>
    <button class="submit-btn" onclick="submitExam()">Submit Test</button>
</div>

<script>
let answers = {};
let timerInterval;
let categoryId = <?php echo $category_id; ?>;
let studentId = <?php echo $student_id; ?>;

function startTimer(duration, display) {
    let timer = duration, minutes, seconds;
    timerInterval = setInterval(() => {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);
        display.textContent = minutes + ":" + (seconds < 10 ? "0" + seconds : seconds);
        if (--timer < 0) {
            clearInterval(timerInterval);
            submitExam();
        }
    }, 1000);
}

// Load questions
fetch(`api/get_questions.php?category_id=${categoryId}`)
    .then(res => {
        if(!res.ok) throw new Error('Network response was not ok');
        return res.json();
    })
    .then(questions => {
        if(!questions || questions.length === 0) {
            document.getElementById("questionsArea").innerHTML = '<div class="error-msg">No questions found for this test. Please contact admin.</div>';
            return;
        }
        
        let html = "";
        questions.forEach((q, idx) => {
            html += `<div class="question-card">
                        <p><strong>${idx+1}. ${escapeHtml(q.question_text)}</strong></p>
                        <div class="options">
                            <label><input type="radio" name="q${q.id}" value="A"> A. ${escapeHtml(q.option_a)}</label>
                            <label><input type="radio" name="q${q.id}" value="B"> B. ${escapeHtml(q.option_b)}</label>
                            <label><input type="radio" name="q${q.id}" value="C"> C. ${escapeHtml(q.option_c)}</label>
                            <label><input type="radio" name="q${q.id}" value="D"> D. ${escapeHtml(q.option_d)}</label>
                        </div>
                      </div>`;
        });
        document.getElementById("questionsArea").innerHTML = html;
        
        // Collect answers when radio buttons change
        document.querySelectorAll("input[type=radio]").forEach(radio => {
            radio.addEventListener("change", (e) => {
                let name = e.target.name;
                answers[name] = e.target.value;
            });
        });
    })
    .catch(error => {
        console.error("Error loading questions:", error);
        document.getElementById("questionsArea").innerHTML = '<div class="error-msg">Error loading questions. Please refresh the page.</div>';
    });

function submitExam() {
    // Check if any answers selected
    let answerCount = Object.keys(answers).length;
    if(answerCount === 0) {
        alert("Please answer at least one question before submitting!");
        return;
    }
    
    clearInterval(timerInterval);
    let answerData = [];
    for(let key in answers) {
        answerData.push({qid: key.replace("q",""), ans: answers[key]});
    }
    
    fetch("api/submit_exam.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            student_id: studentId,
            category_id: categoryId,
            answers: answerData
        })
    })
    .then(res => res.text())
    .then(data => {
        alert("Test submitted! Your score: " + data);
        window.location.href = "student_dashboard.php";
    })
    .catch(error => {
        console.error("Error submitting exam:", error);
        alert("Error submitting exam. Please try again.");
    });
}

// Helper function to prevent XSS attacks
function escapeHtml(str) {
    if(!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if(m === '&') return '&amp;';
        if(m === '<') return '&lt;';
        if(m === '>') return '&gt;';
        return m;
    });
}

window.onload = () => {
    let display = document.querySelector("#timer");
    startTimer(900, display); // 15 minutes = 900 seconds
};
</script>
</body>
</html>