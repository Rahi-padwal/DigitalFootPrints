<?php
function getSecurityHeaders($url) {
    $headers = @get_headers($url, 1);

    if ($headers === false) {
        return "❌ Error: Unable to fetch headers for $url - Headers not accessible.";
    }

    $securityHeaders = [
        "Strict-Transport-Security",
        "Content-Security-Policy",
        "X-Frame-Options",
        "X-Content-Type-Options"
    ];

    $missingHeaders = [];
    foreach ($securityHeaders as $header) {
        if (!isset($headers[$header])) {
            $missingHeaders[] = "⚠️ Missing: $header";
        }
    }

    return empty($missingHeaders) ? "✅ All security headers are present!" : implode("\n", $missingHeaders);
}
?>
