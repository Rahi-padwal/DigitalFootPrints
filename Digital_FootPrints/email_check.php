<?php
header("Content-Type: application/json");

// List of known risky and disposable email domains
$risky_domains = ["spam4u.net", "cock.li", "shadyemail.com", "darkwebmail.com"];
$disposable_domains = ["mailinator.com", "tempmail.com", "yopmail.com", "guerrillamail.com","bitflirt.com"];

// Get email from request
if (!isset($_POST['email']) || empty($_POST['email'])) {
    echo json_encode(["message" => "Please enter a valid email.", "valid" => false]);
    exit;
}

$email = trim($_POST['email']);
$email_parts = explode("@", $email);
$domain = strtolower(end($email_parts));

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["message" => "Invalid email format.", "valid" => false]);
    exit;
}

// Check if domain is risky or disposable
$is_risky = in_array($domain, $risky_domains);
$is_disposable = in_array($domain, $disposable_domains);

$response = [
    "message" => "Email check completed.",
    "valid" => true,
    "disposable" => $is_disposable,
    "temporary" => $is_disposable,
    "risky" => $is_risky
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
