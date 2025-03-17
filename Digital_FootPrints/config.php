<?php
$servername = "localhost";
$username = "root";  // Change this if using a different username
$password = "";      // Change this if using a password
$database = "digitalfootprints"; // Database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
