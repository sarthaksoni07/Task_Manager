<?php

require_once __DIR__ . '/common.php';

if (!isset($_SESSION['user_id'])) {
    send_json(['ok' => true, 'loggedIn' => false]);
}

send_json([
    'ok' => true,
    'loggedIn' => true,
    'user' => ['id' => $_SESSION['user_id'], 'username' => $_SESSION['username']]
]);
