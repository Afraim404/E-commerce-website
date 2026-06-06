<?php
require_once dirname(__DIR__) . '/config.php';
$db = getDB();

if ($method === 'GET') {
    if ($id) {
        $stmt = $db->prepare("SELECT * FROM orders WHERE id=?");
        $stmt->execute([intval($id)]);
        $order = $stmt->fetch();
        if (!$order) jsonError('Order not found', 404);
        $order['items'] = json_decode($order['items'] ?? '[]', true) ?: [];
        jsonSuccess($order);
    }
    requireAdmin();
    $status = $_GET['status'] ?? 'all';
    $sql    = "SELECT * FROM orders";
    $params = [];
    if ($status !== 'all') { $sql .= " WHERE status=?"; $params[] = $status; }
    $sql .= " ORDER BY created_at DESC";
    $stmt = $db->prepare($sql); $stmt->execute($params);
    $orders = $stmt->fetchAll();
    foreach ($orders as &$o) $o['items'] = json_decode($o['items'], true) ?: [];
    jsonSuccess($orders);
}

if ($method === 'POST') {
    $input = getInput();
    $name  = sanitize($input['customer_name'] ?? '');
    $phone = sanitize($input['customer_phone'] ?? '');
    $items = $input['items'] ?? [];
    $total = floatval($input['total'] ?? 0);
    if (!$name || !$phone || empty($items)) jsonError('Name, phone and items required');

    // Generate order code
    $lastOrder = $db->query("SELECT COUNT(*) as c FROM orders")->fetch();
    $code = 'ORD' . str_pad($lastOrder['c'] + 1, 4, '0', STR_PAD_LEFT);

    $coupon  = sanitize($input['coupon'] ?? '');
    $discount = 0;
    if ($coupon) {
        $coupStmt = $db->prepare("SELECT * FROM coupons WHERE code=? AND active=1");
        $coupStmt->execute([$coupon]);
        $c = $coupStmt->fetch();
        if ($c) {
            $db->prepare("UPDATE coupons SET uses=uses+1 WHERE id=?")->execute([$c['id']]);
            if ($c['type'] === 'percent') $discount = $total * ($c['value']/100);
            elseif ($c['type'] === 'fixed') $discount = $c['value'];
        }
    }

    $stmt = $db->prepare("INSERT INTO orders (order_code,customer_name,customer_phone,customer_address,items,subtotal,discount,delivery_charge,total,payment_method,coupon_used,status,notes,city,district,postcode,customer_email) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $subtotal       = floatval($input['subtotal'] ?? ($total - $discount));
    $delivery       = floatval($input['delivery_charge'] ?? 80);
    $finalTotal     = floatval($input['total'] ?? ($total - $discount + $delivery));
    $stmt->execute([
        $code,
        $name,
        $phone,
        sanitize($input['address'] ?? ''),
        json_encode($items),
        $subtotal,
        $discount,
        $delivery,
        $finalTotal,
        sanitize($input['payment_method'] ?? 'COD'),
        $coupon ?: null,
        'new',
        sanitize($input['notes'] ?? ''),
        sanitize($input['city'] ?? ''),
        sanitize($input['district'] ?? ''),
        sanitize($input['postcode'] ?? ''),
        sanitize($input['customer_email'] ?? ''),
    ]);
    $newId = $db->lastInsertId();

    // Upsert customer
    $custStmt = $db->prepare("SELECT id FROM customers WHERE phone=?");
    $custStmt->execute([$phone]);
    $cust = $custStmt->fetch();
    if ($cust) {
        $db->prepare("UPDATE customers SET total_orders=total_orders+1, total_spent=total_spent+? WHERE phone=?")->execute([$total, $phone]);
    } else {
        $db->prepare("INSERT INTO customers (name,phone,location,total_orders,total_spent) VALUES (?,?,?,1,?)")->execute([$name,$phone,sanitize($input['location']??''),$total]);
    }

    jsonSuccess(['order_id' => $newId, 'order_code' => $code], 'Order placed successfully');
}

if ($method === 'PUT') {
    requireAdmin();
    if (!$id) jsonError('Order ID required');
    $input = getInput();
    $status = sanitize($input['status'] ?? '');
    $notes  = sanitize($input['notes'] ?? '');
    if ($status) $db->prepare("UPDATE orders SET status=?, notes=? WHERE id=?")->execute([$status, $notes, intval($id)]);
    jsonSuccess([], 'Order updated');
}

if ($method === 'DELETE') {
    requireAdmin();
    if (!$id) jsonError('Order ID required');
    $db->prepare("DELETE FROM orders WHERE id=?")->execute([intval($id)]);
    jsonSuccess([], 'Order deleted');
}
