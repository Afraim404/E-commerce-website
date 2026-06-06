<?php
require_once dirname(__DIR__) . '/config.php';
$db = getDB();

if ($method === 'GET') {
    $cat    = $_GET['cat'] ?? 'all';
    $search = $_GET['search'] ?? '';
    $active = $_GET['active'] ?? '1';
    $sql    = "SELECT * FROM products WHERE 1=1";
    $params = [];
    if ($active !== 'all') { $sql .= " AND active=?"; $params[] = 1; }
    if ($cat && $cat !== 'all') { $sql .= " AND cat=?"; $params[] = $cat; }
    if ($search) { $sql .= " AND (name LIKE ? OR description LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
    $sql .= " ORDER BY created_at DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
    foreach ($products as &$p) {
        $p['images'] = json_decode($p['images'] ?? '[]', true) ?: [];
        $p['sizes']  = json_decode($p['sizes']  ?? '[]', true) ?: [];
    }
    jsonSuccess($products);
}

if ($method === 'POST') {
    requireAdmin();
    $input = getInput();
    $name  = sanitize($input['name'] ?? '');
    $price = floatval($input['price'] ?? 0);
    if (!$name || !$price) jsonError('Name and price are required');
    $sizes = $input['sizes'] ?? [];
    if (is_string($sizes)) $sizes = array_filter(array_map('trim', explode(',', strtoupper($sizes))));
    $sizes = array_values(array_unique(array_filter($sizes)));
    $stmt = $db->prepare("INSERT INTO products (name,cat,price,old_price,badge,emoji,description,images,sizes,stock,active) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
        $name, sanitize($input['cat'] ?? 'tshirt'), $price,
        $input['old_price'] ? floatval($input['old_price']) : null,
        $input['badge'] ? strtoupper(sanitize($input['badge'])) : null,
        sanitize($input['emoji'] ?? '👕'),
        sanitize($input['description'] ?? ''),
        json_encode($input['images'] ?? []),
        json_encode($sizes),
        intval($input['stock'] ?? 100), 1,
    ]);
    $newId = $db->lastInsertId();
    $product = $db->query("SELECT * FROM products WHERE id=$newId")->fetch();
    $product['images'] = json_decode($product['images'], true) ?: [];
    $product['sizes']  = json_decode($product['sizes'],  true) ?: [];
    jsonSuccess($product, 'Product added');
}

if ($method === 'PUT') {
    requireAdmin();
    if (!$id) jsonError('Product ID required');
    $input = getInput();
    $fields = []; $params = [];
    $allowed = ['name','cat','price','old_price','badge','emoji','description','stock','active'];
    foreach ($allowed as $f) {
        if (isset($input[$f])) {
            $fields[] = "$f=?";
            $params[] = in_array($f, ['price','old_price','stock','active'])
                ? ($input[$f]===''||$input[$f]===null?null:($f==='active'?intval($input[$f]):floatval($input[$f])))
                : sanitize($input[$f]);
        }
    }
    if (isset($input['images'])) { $fields[] = "images=?"; $params[] = json_encode($input['images']); }
    if (isset($input['sizes'])) {
        $sizes = $input['sizes'];
        if (is_string($sizes)) $sizes = array_filter(array_map('trim', explode(',', strtoupper($sizes))));
        $sizes = array_values(array_unique(array_filter($sizes)));
        $fields[] = "sizes=?"; $params[] = json_encode($sizes);
    }
    if (empty($fields)) jsonError('Nothing to update');
    $params[] = intval($id);
    $db->prepare("UPDATE products SET " . implode(',', $fields) . " WHERE id=?")->execute($params);
    $product = $db->query("SELECT * FROM products WHERE id=" . intval($id))->fetch();
    $product['images'] = json_decode($product['images'], true) ?: [];
    $product['sizes']  = json_decode($product['sizes'],  true) ?: [];
    jsonSuccess($product, 'Product updated');
}

if ($method === 'DELETE') {
    requireAdmin();
    if (!$id) jsonError('Product ID required');
    $db->prepare("DELETE FROM products WHERE id=?")->execute([intval($id)]);
    jsonSuccess([], 'Product deleted');
}
