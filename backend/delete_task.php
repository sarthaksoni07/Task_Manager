<?php
include "config.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$id = $_POST['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);

echo json_encode(["success" => $stmt->execute()]);
?>