<?php
include "config.php";

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (!$username || !$email || !$password) {
    echo json_encode(["success" => false, "message" => "All fields required"]);
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hash);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "User exists"]);
}
?>