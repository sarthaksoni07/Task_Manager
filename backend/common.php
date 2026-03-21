<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (isset($_SERVER['HTTP_ORIGIN'])) {
    $origin = $_SERVER['HTTP_ORIGIN'];
    $isLocalhost = preg_match('/^http:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/', $origin) === 1;

    if ($isLocalhost) {
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Access-Control-Allow-Credentials: true');
    }
}

header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/db.php';

function send_json($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

function require_post() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        send_json(['ok' => false, 'message' => 'Method not allowed'], 405);
    }
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        send_json(['ok' => false, 'message' => 'Unauthorized'], 401);
    }
}

function post($key) {
    return trim($_POST[$key] ?? '');
}
