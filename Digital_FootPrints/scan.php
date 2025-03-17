<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $website = filter_var($_POST["website"], FILTER_SANITIZE_URL);
    $parsed_url = parse_url($website, PHP_URL_HOST);

    include 'security_headers.php';
    include 'ssl_check.php';
    include 'hsts_check.php';

    echo "<h3>🔍 Scan Results: </h3>";

    // ✅ Check if website is reachable
    $ch = curl_init($website);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Changed to true for SSL validation
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: Mozilla/5.0"]);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        echo "<p>❌ Error: Unable to connect. Reason: $error</p>";
        exit;
    } else {
        echo "<p>✅ Connection successful! HTTP Code: $http_code</p>";
    }

    // ✅ Fetch Security Headers Properly
    $securityHeaders = getSecurityHeaders($website);
    echo "<div class='result'><h4>🔐 Security Headers</h4><pre>" . htmlspecialchars($securityHeaders) . "</pre></div>";

    // ✅ HSTS Check
    $hstsResult = checkHSTS($website);
    if (strpos($securityHeaders, "Strict-Transport-Security") !== false) {
        $hstsResult = "✅ HSTS Header Found!";
    }
    echo "<div class='result'><h4>📜 HSTS Check</h4><pre>$hstsResult</pre></div>";

    // ✅ Fetch & Format SSL Certificate Info
    $sslDetails = checkSSL($parsed_url);
    $sslDetails = json_decode($sslDetails, true);

    echo "<div class='result'><h4>🔒 SSL Certificate</h4>";
    if (!$sslDetails || !is_array($sslDetails)) {
        echo "<pre>❌ No SSL Certificate detected or failed to fetch details.</pre>";
    } else {
        echo "<pre>Issuer: " . htmlspecialchars($sslDetails["Issuer"] ?? "Unknown") . "</pre>";
        echo "<pre>Valid From: " . htmlspecialchars($sslDetails["Valid From"] ?? "N/A") . "</pre>";
        echo "<pre>Valid To: " . htmlspecialchars($sslDetails["Valid To"] ?? "N/A") . "</pre>";
        echo "<pre>Encryption: " . htmlspecialchars($sslDetails["Encryption"] ?? "Unknown") . "</pre>";
    }
    echo "</div>";
}
?>
