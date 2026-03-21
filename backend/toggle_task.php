<?php

require_once __DIR__ . '/common.php';
require_post();
require_login();

$taskId = (int) ($_POST['task_id'] ?? 0);
if ($taskId <= 0) {
    send_json(['ok' => false, 'message' => 'Invalid task id'], 400);
}

$conn = db();

$find = $conn->prepare('SELECT status FROM tasks WHERE id = ? AND user_id = ? LIMIT 1');
$find->bind_param('ii', $taskId, $_SESSION['user_id']);
$find->execute();
$task = $find->get_result()->fetch_assoc();
$find->close();

if (!$task) {
    $conn->close();
    send_json(['ok' => false, 'message' => 'Task not found'], 404);
}

$newStatus = $task['status'] === 'completed' ? 'pending' : 'completed';
$completedAt = $newStatus === 'completed' ? date('Y-m-d H:i:s') : null;

$update = $conn->prepare('UPDATE tasks SET status = ?, completed_at = ? WHERE id = ? AND user_id = ?');
$update->bind_param('ssii', $newStatus, $completedAt, $taskId, $_SESSION['user_id']);

if (!$update->execute()) {
    $update->close();
    $conn->close();
    send_json(['ok' => false, 'message' => 'Update failed'], 500);
}

$update->close();
$conn->close();

send_json(['ok' => true, 'message' => 'Task updated']);
