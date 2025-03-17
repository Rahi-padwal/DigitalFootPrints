function checkPassword() {
    let password = document.getElementById("password").value;

    if (!password) {
        alert("Please enter a password.");
        return;
    }

    fetch("breached_password.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "password=" + encodeURIComponent(password)
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("result").innerHTML = data;
    })
    .catch(error => {
        console.error("Error:", error);
        document.getElementById("result").innerHTML = "An error occurred. Please try again.";
    });
}