<?php
// coupons.php
require_once dirname(__DIR__) . '/config.php';
$db = getDB();

if ($method === 'GET') {
    $active = $_GET['active'] ?? null;
    $sql = "SELECT * FROM coupons"; $params = [];
    if ($active !== null) { $sql .= " WHERE active=?"; $params[] = 1; }
    $stmt = $db->prepare($sql); $stmt->execute($params);
    jsonSuccess($stmt->fetchAll());
}
if ($method === 'POST') {
    requireAdmin();
    $input = getInput();
    $code = strtoupper(sanitize($input['code'] ?? ''));
    if (!$code) jsonError('Coupon code required');
    try {
        $stmt = $db->prepare("INSERT INTO coupons (code,type,value,min_items,active) VALUES (?,?,?,?,1)");
        $stmt->execute([$code, $input['type']??'free_delivery', floatval($input['value']??0), intval($input['min_items']??1)]);
        jsonSuccess(['id' => $db->lastInsertId()], 'Coupon created');
    } catch (Exception $e) { jsonError('Coupon code already exists'); }
}
if ($method === 'PUT') {
    requireAdmin(); if (!$id) jsonError('ID required');
    $input = getInput();
    if (isset($input['active'])) $db->prepare("UPDATE coupons SET active=? WHERE id=?")->execute([intval($input['active']), intval($id)]);
    jsonSuccess([], 'Coupon updated');
}
if ($method === 'DELETE') {
    requireAdmin(); if (!$id) jsonError('ID required');
    $db->prepare("DELETE FROM coupons WHERE id=?")->execute([intval($id)]);
    jsonSuccess([], 'Coupon deleted');
}
