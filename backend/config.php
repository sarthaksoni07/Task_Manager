<?php
$conn = new mysqli("localhost", "root", "", "task_manager");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

header("Content-Type: application/json");
?>