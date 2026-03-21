<?php

require_once __DIR__ . '/common.php';
require_post();
require_login();

$title = post('title');
if ($title === '') {
    send_json(['ok' => false, 'message' => 'Task title is required'], 400);
}

$conn = db();
$stmt = $conn->prepare('INSERT INTO tasks (user_id, title, status) VALUES (?, ?, "pending")');
$stmt->bind_param('is', $_SESSION['user_id'], $title);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    send_json(['ok' => false, 'message' => 'Create failed'], 500);
}

$stmt->close();
$conn->close();

send_json(['ok' => true, 'message' => 'Task created']);
