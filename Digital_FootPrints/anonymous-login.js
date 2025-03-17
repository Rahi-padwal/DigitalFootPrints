document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("checkAnonymousLogin").addEventListener("click", function () {
        let email = document.getElementById("anonymousEmail").value;

        if (!email) {
            alert("Please enter an email.");
            return;
        }

        fetch("email_check.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `email=${encodeURIComponent(email)}`
        })
        .then(response => response.json())
        .then(data => {
            console.log("Response Data:", data); // Debugging

            if (!data.valid) {
                alert(data.message);
                return;
            }

            let resultHTML = `<strong>${data.message}</strong><br><br>
                <ul>
                    <li><strong>Valid Email:</strong> ${data.valid ? "✅ Yes" : "❌ No"}</li>
                    <li><strong>Disposable Email:</strong> ${data.disposable ? "❌ Yes" : "✅ No"}</li>
                    <li><strong>Temporary Email:</strong> ${data.temporary ? "❌ Yes" : "✅ No"}</li>
                    <li><strong>Risky Domain:</strong> ${data.risky ? "❌ Yes" : "✅ No"}</li>
                </ul>`;

            document.getElementById("anonymousLoginResult").innerHTML = resultHTML;
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            document.getElementById("anonymousLoginResult").innerHTML = "<strong>Error:</strong> Something went wrong.";
        });
    });
});
