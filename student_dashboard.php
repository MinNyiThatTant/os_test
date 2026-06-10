<?php
session_start();
if(!isset($_SESSION['student_id'])) {
    header("Location: index.html");
    exit();
}
require_once 'includes/db_connect.php';
$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'] ?? 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - OS Exam</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .test-card {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            margin: 15px 0;
            border-radius: 20px;
            transition: 0.3s;
        }
        .test-card.available {
            cursor: pointer;
        }
        .test-card.available:hover {
            background: rgba(255,255,255,0.2);
            transform: scale(1.02);
        }
        .test-card.completed {
            background: rgba(46, 204, 113, 0.2);
            border-left: 5px solid #2ecc71;
            cursor: default;
        }
        .test-card.disabled {
            background: rgba(231, 76, 60, 0.2);
            cursor: default;
            opacity: 0.7;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-top: 10px;
        }
        .badge.completed { background: #2ecc71; color: #fff; }
        .badge.available { background: #3498db; color: #fff; }
        .badge.disabled { background: #e74c3c; color: #fff; }
        .loading { text-align: center; padding: 40px; }
        .refresh-btn {
            background: #3498db;
            padding: 8px 20px;
            border: none;
            border-radius: 25px;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container" style="max-width: 800px;">
    <h2>Welcome, <?php echo htmlspecialchars($student_name); ?>!</h2>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <p>Click on any available test to begin:</p>
        <button class="refresh-btn" onclick="location.reload()">Refresh</button>
    </div>
    <div id="testsList">
        <div class="loading">Loading tests...</div>
    </div>
    <div style="margin-top: 20px; text-align: center;">
        <a href="logout.php" style="color: #ff9999;">Logout</a>
    </div>
</div>

<script>
let studentId = <?php echo $student_id; ?>;

async function loadTests() {
    document.getElementById("testsList").innerHTML = '<div class="loading">Loading tests...</div>';
    
    try {
        // Fetch all tests
        const response = await fetch(`api/get_student_tests.php?student_id=${studentId}`);
        const tests = await response.json();
        
        // Fetch attempted tests
        const attemptRes = await fetch('api/check_attempt.php');
        const attempts = await attemptRes.json();
        
        // Create map of attempted tests
        let attemptedMap = {};
        if (Array.isArray(attempts)) {
            attempts.forEach(a => {
                attemptedMap[a.id] = a;
            });
        }
        
        // Build HTML
        let html = "";
        for (let test of tests) {
            let attempted = attemptedMap[test.id];
            let questionCount = parseInt(test.question_count) || 0;
            let isAvailable = !attempted && questionCount > 0;
            
            if (isAvailable) {
                // Available test - clickable
                html += `
                    <div class="test-card available" data-id="${test.id}">
                        <h3>${escapeHtml(test.name)}</h3>
                        <p>${escapeHtml(test.description || 'No description')}</p>
                        <p>Questions: ${questionCount}</p>
                        <span class="badge available">Available (${questionCount} questions)</span>
                    </div>
                `;
            } else if (attempted) {
                // Completed test - show score
                html += `
                    <div class="test-card completed">
                        <h3>${escapeHtml(test.name)}</h3>
                        <p>${escapeHtml(test.description || 'No description')}</p>
                        <p>Questions: ${questionCount}</p>
                        <span class="badge completed">Completed</span>
                        <p style="margin-top: 10px; font-size: 0.9rem;">Your score: ${attempted.score}/${attempted.total_questions}</p>
                    </div>
                `;
            } else {
                // No questions - disabled
                html += `
                    <div class="test-card disabled">
                        <h3>${escapeHtml(test.name)}</h3>
                        <p>${escapeHtml(test.description || 'No description')}</p>
                        <p>Questions: ${questionCount}</p>
                        <span class="badge disabled">No questions</span>
                    </div>
                `;
            }
        }
        
        if (tests.length === 0) {
            html = '<div class="loading">No tests available. Please contact admin.</div>';
        }
        
        document.getElementById("testsList").innerHTML = html;
        
        // Add click listeners to available tests
        document.querySelectorAll('.test-card.available').forEach(card => {
            card.addEventListener('click', function() {
                let testId = this.getAttribute('data-id');
                if (testId) {
                    window.location.href = `take_exam.php?category_id=${testId}`;
                }
            });
        });
        
    } catch (error) {
        console.error("Error:", error);
        document.getElementById("testsList").innerHTML = `
            <div class="loading">
                <p>Error loading tests: ${error.message}</p>
                <button class="refresh-btn" onclick="loadTests()">Retry</button>
            </div>
        `;
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Load tests when page opens
loadTests();
</script>
</body>
</html>