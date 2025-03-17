<?php
function checkSSL($domain) {
    $ctx = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
    $read = @stream_socket_client("ssl://".$domain.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $ctx);

    if (!$read) {
        return json_encode(["error" => "❌ No SSL Certificate detected. Error: $errstr"], JSON_PRETTY_PRINT);
    }

    $cert = stream_context_get_params($read)["options"]["ssl"]["peer_certificate"];
    $certInfo = openssl_x509_parse($cert);

    return json_encode([
        "Issuer" => $certInfo["issuer"]["O"] ?? "Unknown",
        "Valid From" => date("Y-m-d", $certInfo["validFrom_time_t"]),
        "Valid To" => date("Y-m-d", $certInfo["validTo_time_t"]),
        "Encryption" => $certInfo["signatureTypeSN"] ?? "Unknown"
    ], JSON_PRETTY_PRINT);
}
?>
