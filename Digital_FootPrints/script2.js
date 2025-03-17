document.getElementById('emailForm').addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent form from resetting

    const email = document.getElementById('emailInput').value;
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = "<p>Checking...</p>";

    try {
        const response = await fetch('breached_email.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email })
        });

        const result = await response.json();
        if (response.ok) {
            if (result.found) {
                resultDiv.innerHTML = `<p style="color: red;">This email has been pwned in ${result.breaches} breaches.</p>`;
            } else {
                resultDiv.innerHTML = `<p style="color: green;">This email has not been pwned.</p>`;
            }
        } else {
            resultDiv.innerHTML = `<p class="error">Error: ${result.message}</p>`;
        }
    } catch (error) {
        resultDiv.innerHTML = `<p class="error">An error occurred while checking the email.</p>`;
        console.error('Error:', error);
    }
});
