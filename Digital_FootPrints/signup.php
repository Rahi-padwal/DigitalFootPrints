<?php
session_start();
include 'config.php';  // Use existing database connection

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Check if email already exists
$check = $conn->prepare("SELECT ID FROM dfusers WHERE Email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Email already exists!"]);
    exit();
}

// Hash password before storing
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO dfusers (Name, Email, Password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Registration failed"]);
}
?>
