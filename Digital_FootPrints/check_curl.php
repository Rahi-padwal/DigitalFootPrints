<?php
// Check if cURL is enabled
if (function_exists('curl_version')) {
    echo '✅ cURL is enabled!';
} else {
    echo '❌ cURL is not enabled. Enable it in php.ini by removing ";" from ";extension=curl".';
}
?>

