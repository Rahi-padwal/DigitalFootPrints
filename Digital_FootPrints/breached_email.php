<?php
// Set response headers
header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
    exit;
}

// Get the JSON input from the request
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['email'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid request data']);
    exit;
}

// Validate email input
$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
if (!$email) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid email address']);
    exit;
}

// HIBP API details
$api_key = 'fd5bbdaa6971465ba45c40ba3fc46e4f'; // Replace with your actual API key
$hibp_url = "https://haveibeenpwned.com/api/v3/breachedaccount/{$email}?truncateResponse=true";

// Prepare and send the cURL request
$ch = curl_init($hibp_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "hibp-api-key: {$api_key}",
    "User-Agent: PHP cURL"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Handle response based on HTTP status code
if ($http_code === 200) {
    $breach_data = json_decode($response, true);
    $breach_count = count($breach_data);
    echo json_encode([
        'found' => true,
        'breaches' => $breach_count
    ]);
} elseif ($http_code === 404) {
    echo json_encode([
        'found' => false
    ]);
} else {
    http_response_code($http_code);
    echo json_encode(['message' => 'Error retrieving data from HIBP']);
}