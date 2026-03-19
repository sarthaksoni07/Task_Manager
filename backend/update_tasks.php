<?php
include "config.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$id = $_POST['id'];
$status = $_POST['status'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE tasks SET status=? WHERE id=? AND user_id=?");
$stmt->bind_param("sii", $status, $id, $user_id);

echo json_encode(["success" => $stmt->execute()]);
?>