<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'task_manager_app');

function db() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'Database connection failed']);
        exit;
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
