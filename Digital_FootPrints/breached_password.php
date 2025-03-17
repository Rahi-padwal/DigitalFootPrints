<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST["password"];
    
    if (!empty($password)) {
        $sha1 = strtoupper(sha1($password));
        $prefix = substr($sha1, 0, 5);
        $suffix = substr($sha1, 5);
        
        $url = "https://api.pwnedpasswords.com/range/" . $prefix;
        $response = file_get_contents($url);
        
        if ($response !== false) {
            $lines = explode("\n", $response);
            $found = false;
            
            foreach ($lines as $line) {
                list($hash, $count) = explode(":", trim($line));
                if ($hash === $suffix) {
                    $found = true;
                    $breach_count = $count;
                    break;
                }
            }
            
            if ($found) {
                echo json_encode(["status" => "breached", "count" => $breach_count]);
            } else {
                echo json_encode(["status" => "safe"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "API request failed."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Password cannot be empty."]);
    }
}
?>
