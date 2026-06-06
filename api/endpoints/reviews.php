<?php
require_once dirname(__DIR__) . '/config.php';
$db = getDB();

if ($method === 'GET') {
    $status = $_GET['status'] ?? 'approved';
    $sql = "SELECT * FROM reviews"; $params = [];
    if ($status !== 'all') { $sql .= " WHERE status=?"; $params[] = $status; }
    $sql .= " ORDER BY created_at DESC";
    $stmt = $db->prepare($sql); $stmt->execute($params);
    jsonSuccess($stmt->fetchAll());
}
if ($method === 'POST') {
    $input = getInput();
    $name = sanitize($input['customer_name'] ?? '');
    $text = sanitize($input['review_text'] ?? '');
    if (!$name || !$text) jsonError('Name and review text required');
    $stmt = $db->prepare("INSERT INTO reviews (customer_name,product_name,product_id,rating,review_text,status) VALUES (?,?,?,?,?,'pending')");
    $stmt->execute([$name, sanitize($input['product_name']??''), $input['product_id']??null, intval($input['rating']??5), $text]);
    jsonSuccess(['id' => $db->lastInsertId()], 'Review submitted for approval');
}
if ($method === 'PUT') {
    requireAdmin(); if (!$id) jsonError('ID required');
    $input = getInput();
    $status = sanitize($input['status'] ?? 'approved');
    $db->prepare("UPDATE reviews SET status=? WHERE id=?")->execute([$status, intval($id)]);
    jsonSuccess([], 'Review updated');
}
if ($method === 'DELETE') {
    requireAdmin(); if (!$id) jsonError('ID required');
    $db->prepare("DELETE FROM reviews WHERE id=?")->execute([intval($id)]);
    jsonSuccess([], 'Review deleted');
}
