function login() {
    let roll = document.getElementById("roll_no").value;
    let pass = document.getElementById("password").value;
    fetch("login.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `roll_no=${roll}&password=${pass}`
    })
    .then(res => res.text())
    .then(data => {
        if(data === "success") {
            window.location.href = "dashboard.html?roll=" + roll;
        } else {
            alert("Invalid credentials");
        }
    });
}

function register() {
    let name = document.getElementById("fullname").value;
    let roll = document.getElementById("new_roll").value;
    let pass = document.getElementById("new_pass").value;
    fetch("register.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `fullname=${name}&roll_no=${roll}&password=${pass}`
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        if(data === "Registered successfully") {
            hideRegister();
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