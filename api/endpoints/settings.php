<?php
require_once dirname(__DIR__) . '/config.php';
$db = getDB();

if ($method === 'GET') {
    $key = $_GET['key'] ?? null;
    if ($key) {
        $stmt = $db->prepare("SELECT value FROM settings WHERE `key`=?");
        $stmt->execute([sanitize($key)]);
        $r = $stmt->fetch();
        jsonSuccess(['value' => $r ? $r['value'] : '']);
    }
    // Return public settings only
    $allowed = ['store_name','whatsapp','sale_end'];
    $result = [];
    foreach ($allowed as $k) {
        $stmt = $db->prepare("SELECT value FROM settings WHERE `key`=?");
        $stmt->execute([$k]);
        $r = $stmt->fetch();
        if ($r) $result[$k] = $r['value'];
    }
    jsonSuccess($result);
}

if ($method === 'POST') {
    requireAdmin();
    $input = getInput();
    foreach ($input as $k => $v) {
        $k = sanitize($k);
        if ($k === 'admin_pass') {
            // Verify old pass
            $stored = $db->query("SELECT value FROM settings WHERE `key`='admin_pass'")->fetch();
            if (!$stored || $input['old_pass'] !== $stored['value']) jsonError('Wrong current password');
            $v = sanitize($v);
        }
        $db->prepare("INSERT INTO settings (`key`,`value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `value`=?")->execute([$k, $v, $v]);
    }
    jsonSuccess([], 'Settings saved');
}
