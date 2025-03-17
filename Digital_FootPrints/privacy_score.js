function checkPrivacyScore() {
    let email = document.getElementById("email").value;
    let loading = document.getElementById("loading");
    let error = document.getElementById("error");
    let result = document.getElementById("result");

    if (!email) {
        alert("Please enter a valid email.");
        return;
    }

    loading.style.display = "block";
    error.style.display = "none";
    result.innerText = "";

    fetch("privacy_score.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "email=" + encodeURIComponent(email)
    })
    .then(response => response.json())
    .then(data => {
        loading.style.display = "none";
        if (data.error) {
            error.innerText = "Error: " + data.error;
            error.style.display = "block";
        } else {
            result.innerText = `Privacy Score: ${data.privacy_score}`;
        }
    })
    .catch((err) => {
        loading.style.display = "none";
        error.innerText = "Network error. Could not fetch score.";
        error.style.display = "block";
        console.error("Fetch error:", err);
    });
}
