<?php

require_once __DIR__ . '/common.php';
require_login();

$status = $_GET['status'] ?? 'all';
$conn = db();

if ($status === 'pending' || $status === 'completed') {
    $stmt = $conn->prepare(
        'SELECT id, title, status, created_at, completed_at
         FROM tasks
         WHERE user_id = ? AND status = ?
         ORDER BY created_at DESC'
    );
    $stmt->bind_param('is', $_SESSION['user_id'], $status);
} else {
    $stmt = $conn->prepare(
        "SELECT id, title, status, created_at, completed_at
         FROM tasks
         WHERE user_id = ?
         ORDER BY CASE status WHEN 'pending' THEN 0 ELSE 1 END, created_at DESC"
    );
    $stmt->bind_param('i', $_SESSION['user_id']);
}

$stmt->execute();
$result = $stmt->get_result();
$tasks = [];

while ($row = $result->fetch_assoc()) {
    $row['id'] = (int) $row['id'];
    $tasks[] = $row;
}

$stmt->close();
$conn->close();

send_json(['ok' => true, 'tasks' => $tasks]);
