<?php

require_once __DIR__ . '/common.php';
require_post();

$_SESSION = [];
session_destroy();

send_json(['ok' => true, 'message' => 'Logged out']);
