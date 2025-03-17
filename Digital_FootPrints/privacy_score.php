<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

define("HIBP_API_KEY", "fd5bbdaa6971465ba45c40ba3fc46e4f");
define("ZEROBOUNCE_API_KEY", "y684b2dcd4a074868b56de65d69da585d");

function return_error($message) {
    echo json_encode(["error" => $message]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    return_error("Invalid request method");
}

if (!isset($_POST['email']) || empty($_POST['email'])) {
    return_error("No email provided");
}

$email = $_POST['email'];

// 🛑 Step 1: Check HaveIBeenPwned API
$hibp_url = "https://haveibeenpwned.com/api/v3/breachedaccount/" . urlencode($email);
$hibp_headers = [
    "hibp-api-key: " . HIBP_API_KEY,
    "User-Agent: PrivacyChecker"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $hibp_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $hibp_headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$hibp_response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$isPwned = ($http_code == 200) ? "pwned" : "not pwned";

// 🛑 Step 2: Run Python Script
$python_output = shell_exec("python check_email.py " . escapeshellarg($email));

if (!$python_output) {
    return_error("Python script execution failed");
}

$email_data = json_decode($python_output, true);
$isRisky = $email_data['risky_domain'] ? "yes" : "no";
$isDisposable = $email_data['disposable_domain'] ? "yes" : "no";

// 🛑 Step 3: Calculate Privacy Score
$privacyScore = 100;
if ($isPwned == "pwned") $privacyScore -= 40;
if ($isRisky == "yes") $privacyScore -= 20;
if ($isDisposable == "yes") $privacyScore -= 10;

$privacyScore = max(0, min(100, $privacyScore));

echo json_encode(["privacy_score" => $privacyScore]);
?>
