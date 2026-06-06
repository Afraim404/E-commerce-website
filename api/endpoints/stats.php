<?php
require_once dirname(__DIR__) . '/config.php';
$db = getDB();
requireAdmin();

if ($method === 'GET') {
    $r = $db->query("SELECT COALESCE(SUM(total),0) as s FROM orders WHERE status='done'")->fetch();
    $totalSales   = $r['s'] ?? 0;
    $r = $db->query("SELECT COUNT(*) as c FROM orders")->fetch();
    $totalOrders  = $r['c'] ?? 0;
    $r = $db->query("SELECT COUNT(*) as c FROM products WHERE active=1")->fetch();
    $totalProds   = $r['c'] ?? 0;
    $r = $db->query("SELECT COUNT(*) as c FROM customers")->fetch();
    $totalCusts   = $r['c'] ?? 0;
    $r = $db->query("SELECT COUNT(*) as c FROM orders WHERE status='new'")->fetch();
    $newOrders    = $r['c'] ?? 0;
    $r = $db->query("SELECT COUNT(*) as c FROM orders WHERE status='pending'")->fetch();
    $pendOrders   = $r['c'] ?? 0;

    // Weekly sales (last 7 days)
    $weekly = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $stmt = $db->prepare("SELECT COALESCE(SUM(total),0) as s FROM orders WHERE DATE(created_at)=? AND status='done'");
        $stmt->execute([$date]);
        $r = $stmt->fetch();
        $weekly[] = ['date' => $date, 'day' => date('D', strtotime($date)), 'sales' => round(floatval($r['s'] ?? 0))];
    }

    // Recent orders
    $stmt   = $db->query("SELECT id,order_code,customer_name,total,status,created_at FROM orders ORDER BY created_at DESC LIMIT 5");
    $recent = $stmt->fetchAll();

    jsonSuccess([
        'total_sales'   => round(floatval($totalSales)),
        'total_orders'  => intval($totalOrders),
        'total_products'=> intval($totalProds),
        'total_customers'=> intval($totalCusts),
        'new_orders'    => intval($newOrders),
        'pending_orders'=> intval($pendOrders),
        'weekly_sales'  => $weekly,
        'recent_orders' => $recent,
    ]);
}
