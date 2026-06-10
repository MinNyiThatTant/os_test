<?php
require_once '../includes/db_connect.php';
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tests</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .category-item { background: rgba(255,255,255,0.1); padding: 15px; margin: 10px 0; border-radius: 12px; }
        .delete-btn { background: #e74c3c; padding: 5px 15px; border-radius: 20px; border: none; color: white; cursor: pointer; }
        .edit-btn { background: #f39c12; padding: 5px 15px; border-radius: 20px; border: none; color: white; cursor: pointer; }
    </style>
</head>
<body>
<div class="container" style="max-width: 900px;">
    <h2>Manage Tests</h2>
    <a href="dashboard.php">← Back to Dashboard</a>
    
    <div style="margin: 20px 0; background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px;">
        <h3>Add New Test</h3>
        <input type="text" id="cat_name" placeholder="Test Name" style="width:100%; margin:5px 0; padding:10px;">
        <textarea id="cat_desc" placeholder="Description" style="width:100%; margin:5px 0; padding:10px;"></textarea>
        <button onclick="addCategory()" style="background:#00b894;">Create Test</button>
    </div>
    
    <h3>Existing Tests</h3>
    <div id="categoriesList"></div>
</div>

<script>
function loadCategories() {
    fetch('../api/get_categories.php')
        .then(res => res.json())
        .then(data => {
            let html = "";
            data.forEach(cat => {
                html += `
                    <div class="category-item">
                        <strong>${cat.name}</strong><br>
                        <small>${cat.description || ''}</small><br>
                        <small>Questions: ${cat.question_count || 0}</small><br>
                        <button class="edit-btn" onclick="editCategory(${cat.id}, '${cat.name}', '${cat.description}')">Edit</button>
                        <button class="delete-btn" onclick="deleteCategory(${cat.id})">Delete</button>
                        <button onclick="location.href='manage_questions.php?category_id=${cat.id}'">Manage Questions</button>
                    </div>
                `;
            });
            document.getElementById("categoriesList").innerHTML = html || "<p>No tests yet. Create one above!</p>";
        });
}

function addCategory() {
    let name = document.getElementById("cat_name").value;
    let desc = document.getElementById("cat_desc").value;
    fetch("../api/add_category.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({name: name, description: desc})
    }).then(() => { location.reload(); });
}

function deleteCategory(id) {
    if(confirm("Delete this test and all questions inside?")) {
        fetch("../api/delete_category.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `id=${id}`
        }).then(() => location.reload());
    }
}

function editCategory(id, oldName, oldDesc) {
    let newName = prompt("New test name:", oldName);
    let newDesc = prompt("New description:", oldDesc);
    if(newName && newDesc) {
        fetch("../api/update_category.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: `id=${id}&name=${encodeURIComponent(newName)}&description=${encodeURIComponent(newDesc)}`
        }).then(() => location.reload());
    }
}

loadCategories();
</script>
</body>
</html>