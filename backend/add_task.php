<?php
include "config.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION['user_id'];
$task = $_POST['task'];

$stmt = $conn->prepare("INSERT INTO tasks (user_id, task_text, status) VALUES (?, ?, 'pending')");
$stmt->bind_param("is", $user_id, $task);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>