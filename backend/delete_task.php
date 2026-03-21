<?php

require_once __DIR__ . '/common.php';
require_post();
require_login();

$taskId = (int) ($_POST['task_id'] ?? 0);
if ($taskId <= 0) {
    send_json(['ok' => false, 'message' => 'Invalid task id'], 400);
}

$conn = db();
$stmt = $conn->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $taskId, $_SESSION['user_id']);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    send_json(['ok' => false, 'message' => 'Delete failed'], 500);
}

$affected = $stmt->affected_rows;
$stmt->close();
$conn->close();

if ($affected === 0) {
    send_json(['ok' => false, 'message' => 'Task not found'], 404);
}

send_json(['ok' => true, 'message' => 'Task deleted']);
