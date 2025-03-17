<?php
session_start();
include 'config.php';  // Use existing database connection

$email = $_POST['email'];
$password = $_POST['password'];

// Fetch user details
$stmt = $conn->prepare("SELECT ID, Password FROM dfusers WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $id;
        echo json_encode(["success" => true]);
        exit();
    }
}

echo json_encode(["success" => false, "message" => "Invalid email or password"]);
?>
