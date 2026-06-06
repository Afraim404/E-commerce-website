<?php
require_once dirname(__DIR__) . '/config.php';
$db = getDB();
requireAdmin();

if ($method === 'GET') {
    $stmt = $db->query("SELECT * FROM customers ORDER BY total_spent DESC");
    jsonSuccess($stmt->fetchAll());
}
if ($method === 'POST') {
    $input = getInput();
    $phone = sanitize($input['phone'] ?? '');
    if (!$phone) jsonError('Phone required');
    try {
        $stmt = $db->prepare("INSERT INTO customers (name,phone,location,total_orders,total_spent) VALUES (?,?,?,0,0)");
        $stmt->execute([sanitize($input['name']??'Lead'), $phone, sanitize($input['location']??'')]);
        jsonSuccess(['id' => $db->lastInsertId()], 'Customer added');
    } catch (Exception $e) { jsonError('Phone already exists'); }
}
if ($method === 'DELETE') {
    if (!$id) jsonError('ID required');
    $db->prepare("DELETE FROM customers WHERE id=?")->execute([intval($id)]);
    jsonSuccess([], 'Customer deleted');
}
