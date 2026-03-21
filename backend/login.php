<?php

require_once __DIR__ . '/common.php';
require_post();

$username = post('username');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    send_json(['ok' => false, 'message' => 'Username and password are required'], 400);
}

$conn = db();
$stmt = $conn->prepare('SELECT id, username, password_hash FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$user || !password_verify($password, $user['password_hash'])) {
    send_json(['ok' => false, 'message' => 'Invalid credentials'], 401);
}

$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['username'] = $user['username'];

send_json([
    'ok' => true,
    'message' => 'Logged in',
    'user' => ['id' => $_SESSION['user_id'], 'username' => $_SESSION['username']]
]);
