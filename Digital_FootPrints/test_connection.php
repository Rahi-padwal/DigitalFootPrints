<?php
$url = "https://www.mahadbt.org";  // The URL you're testing

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Follow redirects if needed
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Ignore SSL verification issues temporarily
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36"
));

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($response) {
    echo "✅ Connection successful! The website is reachable.";
} else {
    echo "❌ Error: Unable to connect to $url. Reason: $error";
}
?>
