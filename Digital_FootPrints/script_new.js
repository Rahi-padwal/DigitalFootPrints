document.addEventListener("DOMContentLoaded", function () {
    checkSession(); // Check login status on page load

    // Navigation for Home & Features
    document.getElementById('homeBtn').addEventListener('click', function () {
        document.getElementById('homeSection').scrollIntoView({ behavior: 'smooth' });
    });

    document.getElementById('featuresBtn').addEventListener('click', function () {
        document.getElementById('featuresSection').scrollIntoView({ behavior: 'smooth' });
    });

    //Profile click
    document.getElementById('profileBtn').addEventListener('click', function () {
        window.location.href = "profile.html"; // Redirect to profile page
    });

    // Login Modal
    document.getElementById('signInBtn').addEventListener('click', function () {
        document.getElementById('loginModal').style.display = 'flex';
    });

    document.getElementById('closeLogin').addEventListener('click', function () {
        document.getElementById('loginModal').style.display = 'none';
    });

    // Sign Up Modal
    document.getElementById('signUpBtn').addEventListener('click', function () {
        document.getElementById('signUpModal').style.display = 'flex';
    });

    document.getElementById('closeSignUp').addEventListener('click', function () {
        document.getElementById('signUpModal').style.display = 'none';
    });

    // Switching between Login and Sign Up modals
    document.getElementById('switchToSignUp').addEventListener('click', function () {
        document.getElementById('loginModal').style.display = 'none';
        document.getElementById('signUpModal').style.display = 'flex';
    });

    document.getElementById('switchToSignIn').addEventListener('click', function () {
        document.getElementById('signUpModal').style.display = 'none';
        document.getElementById('loginModal').style.display = 'flex';
    });

    // Login Functionality
    document.getElementById("loginSubmit").addEventListener("click", function () {
        let email = document.getElementById("loginEmail").value;
        let password = document.getElementById("loginPassword").value;

        fetch("login.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `email=${email}&password=${password}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                sessionStorage.setItem("loggedIn", "true");
                location.reload(); // Reload page to show Profile & Logout
            } else {
                alert(data.message);
            }
        });
    });

    // Sign Up Functionality
    document.getElementById("signUpSubmit").addEventListener("click", function () {
        let name = document.getElementById("signUpName").value;
        let email = document.getElementById("signUpEmail").value;
        let password = document.getElementById("signUpPassword").value;

        fetch("signup.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `name=${name}&email=${email}&password=${password}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                sessionStorage.setItem("loggedIn", "true");
                location.reload(); // Reload page to show Profile & Logout
            } else {
                alert(data.message);
            }
        });
    });

    // Logout Functionality
    document.getElementById("logoutBtn").addEventListener("click", function () {
        fetch("logout.php").then(() => {
            sessionStorage.removeItem("loggedIn");
            location.reload(); // Reload to show Sign in & Sign up
        });
    });

    function checkSession() {
        if (sessionStorage.getItem("loggedIn")) {
            document.getElementById("signInBtn").style.display = "none";
            document.getElementById("signUpBtn").style.display = "none";
            document.getElementById("profileBtn").style.display = "inline";
            document.getElementById("logoutBtn").style.display = "inline";
        } else {
            document.getElementById("signInBtn").style.display = "inline";
            document.getElementById("signUpBtn").style.display = "inline";
            document.getElementById("profileBtn").style.display = "none";
            document.getElementById("logoutBtn").style.display = "none";
        }
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Chatbot elements
    const chatbotIcon = document.getElementById("chatbotIcon");
    const chatContainer = document.getElementById("chatContainer");
    const closeChat = document.getElementById("closeChat");
    const chatBody = document.getElementById("chatBody");
    const userInput = document.getElementById("userInput");
    const sendBtn = document.getElementById("sendBtn");

    // Toggle Chatbot
    chatbotIcon.addEventListener("click", function () {
        chatContainer.classList.toggle("show");
        chatContainer.style.display = "block";
    });

    closeChat.addEventListener("click", function () {
        chatContainer.classList.remove("show");
        setTimeout(() => chatContainer.style.display = "none", 300);
    });

    // Handle sending messages
    sendBtn.addEventListener("click", function () {
        sendMessage();
    });

    userInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") sendMessage();
    });

    function sendMessage() {
        let message = userInput.value.trim();
        if (message === "") return;

        // Append user message
        appendMessage("user", message);
        userInput.value = "";

        // Simulate bot response
        setTimeout(() => {
            let response = getBotResponse(message);
            appendMessage("bot", response);
        }, 1000);
    }

    function appendMessage(sender, text) {
        let messageDiv = document.createElement("p");
        messageDiv.classList.add(sender === "user" ? "user-message" : "bot-message");
        messageDiv.textContent = text;
        chatBody.appendChild(messageDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function getBotResponse(userMessage) {
        userMessage = userMessage.toLowerCase();

        if (userMessage.includes("breach") || userMessage.includes("hacked")) {
            return "If you suspect a breach, change your passwords immediately and enable two-factor authentication.";
        } else if (userMessage.includes("secure my account")) {
            return "To secure your account, use a strong, unique password and activate multi-factor authentication.";
        } else if (userMessage.includes("dark web")) {
            return "Dark web monitoring scans leaked databases for your credentials. You can check for breaches using our Digital Footprints feature.";
        } else {
            return "I'm here to help with cybersecurity! Ask me about data breaches, privacy, or securing your accounts.";
        }
    }
});

// Toggle FAQ Answers
document.querySelectorAll(".faq-question").forEach(button => {
    button.addEventListener("click", () => {
        const answer = button.nextElementSibling;
        const isActive = button.classList.contains("active");

        document.querySelectorAll(".faq-question").forEach(q => q.classList.remove("active"));
        document.querySelectorAll(".faq-answer").forEach(a => {
            a.style.maxHeight = null;
            a.style.padding = "0 15px";
        });

        if (!isActive) {
            button.classList.add("active");
            answer.style.maxHeight = answer.scrollHeight + "px";
            answer.style.padding = "15px";
        }
    });
});


// Subscribe Function
function subscribe() {
    let email = document.getElementById("email").value;
    let checkbox = document.getElementById("subscribe").checked;

    if (!email) return alert("Please enter your email.");
    if (!checkbox) return alert("Please check the subscription box.");

    alert("Thank you for subscribing!");
}
