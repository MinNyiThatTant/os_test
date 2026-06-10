function login() {
    let roll = document.getElementById("roll_no").value;
    let pass = document.getElementById("password").value;
    
    fetch("api/login.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `roll_no=${roll}&password=${pass}`
    })
    .then(res => res.text())
    .then(data => {
        if(data === "success") {
            window.location.href = "student_dashboard.php";
        } else {
            alert("Invalid roll number or password");
        }
    });
}

function register() {
    let fullname = document.getElementById("fullname").value;
    let roll = document.getElementById("new_roll").value;
    let pass = document.getElementById("new_pass").value;
    
    if(!fullname || !roll || !pass) {
        alert("Please fill all fields");
        return;
    }
    
    fetch("api/register.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `fullname=${encodeURIComponent(fullname)}&roll_no=${roll}&password=${pass}`
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        if(data === "Registered successfully") {
            hideRegister();
            document.getElementById("roll_no").value = roll;
        }
    });
}

function showRegister() {
    document.querySelector(".form-box").style.display = "none";
    document.getElementById("registerBox").style.display = "block";
}

function hideRegister() {
    document.getElementById("registerBox").style.display = "none";
    document.querySelector(".form-box").style.display = "block";
}