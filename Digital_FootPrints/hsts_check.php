<?php
function checkHSTS($url) {
    $headers = @get_headers($url, 1);

    if ($headers === false) {
        return "❌ Error: Unable to fetch headers for $url";
    }

    if (isset($headers["Strict-Transport-Security"])) {
        return "✅ HSTS Header Found!";
    } else {
        return "⚠️ Security Issue: Missing 'Strict-Transport-Security' header!";
    }
}
?>
