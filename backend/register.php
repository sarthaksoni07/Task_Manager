<?php

require_once __DIR__ . '/common.php';
require_post();

$username = post('username');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    send_json(['ok' => false, 'message' => 'Username and password are required'], 400);
}

$conn = db();

$check = $conn->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
$check->bind_param('s', $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    $conn->close();
    send_json(['ok' => false, 'message' => 'Username already exists'], 409);
}
$check->close();

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
$stmt->bind_param('ss', $username, $passwordHash);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    send_json(['ok' => false, 'message' => 'Register failed'], 500);
}

$_SESSION['user_id'] = $stmt->insert_id;
$_SESSION['username'] = $username;

$stmt->close();
$conn->close();

send_json([
    'ok' => true,
    'message' => 'Registered',
    'user' => ['id' => $_SESSION['user_id'], 'username' => $_SESSION['username']]
]);
