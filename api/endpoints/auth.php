<?php
require_once dirname(__DIR__) . '/config.php';
if(session_status()===PHP_SESSION_NONE) session_start();

$db = getDB();

if ($method === 'POST') {
    $input = getInput();
    $username = sanitize($input['username'] ?? '');
    $password = $input['password'] ?? '';

    $passRow = $db->query("SELECT value FROM settings WHERE `key`='admin_pass'")->fetch();
    $storedPass = $passRow ? $passRow['value'] : 'adam2026';

    if ($username === ADMIN_USERNAME && $password === $storedPass) {
        $_SESSION['adam_admin'] = true;
        jsonSuccess(['role' => 'admin'], 'Login successful');
    } else {
        jsonError('Invalid username or password', 401);
    }
}

if ($method === 'DELETE') {
    session_destroy();
    jsonSuccess([], 'Logged out');
}

if ($method === 'GET') {
    jsonSuccess(['logged_in' => isset($_SESSION['adam_admin']) && $_SESSION['adam_admin']]);
}
