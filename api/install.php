<?php
// ============================================================
//  ADAM Fashion — Database Installer
//  Visit: yourdomain.com/api/install.php  (run ONCE then delete)
// ============================================================
require_once 'config.php';

$db = getDB();

$tables = [
// SETTINGS
"CREATE TABLE IF NOT EXISTS settings (
    `key` VARCHAR(100) PRIMARY KEY,
    `value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

// PRODUCTS
"CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    cat ENUM('tshirt','pants','polo','hoodie','other') DEFAULT 'tshirt',
    price DECIMAL(10,2) NOT NULL,
    old_price DECIMAL(10,2) DEFAULT NULL,
    badge VARCHAR(20) DEFAULT NULL,
    emoji VARCHAR(10) DEFAULT '👕',
    description TEXT,
    images JSON,
    sizes JSON,
    stock INT DEFAULT 100,
    active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

// ORDERS
"CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(20) UNIQUE,
    customer_name VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(255) DEFAULT NULL,
    customer_address TEXT,
    city VARCHAR(100) DEFAULT NULL,
    district VARCHAR(100) DEFAULT NULL,
    postcode VARCHAR(20) DEFAULT NULL,
    items JSON NOT NULL,
    subtotal DECIMAL(10,2) DEFAULT 0,
    discount DECIMAL(10,2) DEFAULT 0,
    delivery_charge DECIMAL(10,2) DEFAULT 80,
    total DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'COD',
    coupon_used VARCHAR(50) DEFAULT NULL,
    status ENUM('new','pending','done','cancel') DEFAULT 'new',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

// CUSTOMERS
"CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    phone VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(255) DEFAULT NULL,
    location VARCHAR(255) DEFAULT NULL,
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

// COUPONS
"CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    type ENUM('free_delivery','percent','fixed') DEFAULT 'free_delivery',
    value DECIMAL(10,2) DEFAULT 0,
    min_items INT DEFAULT 1,
    min_amount DECIMAL(10,2) DEFAULT 0,
    active TINYINT(1) DEFAULT 1,
    uses INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

// REVIEWS
"CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    product_name VARCHAR(255),
    product_id INT DEFAULT NULL,
    rating TINYINT DEFAULT 5,
    review_text TEXT NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

// WISHLIST (session based, stored server side optional)
"CREATE TABLE IF NOT EXISTS abandoned_leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(20) NOT NULL,
    cart_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
];

$errors = [];
foreach ($tables as $sql) {
    try { $db->exec($sql); }
    catch (Exception $e) { $errors[] = $e->getMessage(); }
}

// Add sizes column if upgrading existing install
try { $db->exec("ALTER TABLE products ADD COLUMN sizes JSON DEFAULT NULL"); } catch(Exception $e) {}
// Add new billing columns to orders if upgrading
try { $db->exec("ALTER TABLE orders ADD COLUMN customer_email VARCHAR(255) DEFAULT NULL"); } catch(Exception $e) {}
try { $db->exec("ALTER TABLE orders ADD COLUMN city VARCHAR(100) DEFAULT NULL"); } catch(Exception $e) {}
try { $db->exec("ALTER TABLE orders ADD COLUMN district VARCHAR(100) DEFAULT NULL"); } catch(Exception $e) {}
try { $db->exec("ALTER TABLE orders ADD COLUMN postcode VARCHAR(20) DEFAULT NULL"); } catch(Exception $e) {}
try { $db->exec("ALTER TABLE orders ADD COLUMN subtotal DECIMAL(10,2) DEFAULT 0"); } catch(Exception $e) {}
try { $db->exec("ALTER TABLE orders ADD COLUMN delivery_charge DECIMAL(10,2) DEFAULT 80"); } catch(Exception $e) {}

// Seed default data
$existing = $db->query("SELECT COUNT(*) as c FROM products")->fetch();
if ($existing['c'] == 0) {
    $ts = json_encode(['S','M','L','XL','XXL']);
    $ps = json_encode(['28','30','32','34','36','38']);
    $defaultProducts = [
        ['Classic Black Tee','tshirt',699,999,'HOT','👕','100% cotton everyday tee',$ts],
        ['Street Graphic Tee','tshirt',849,1200,'NEW','👕','Bold streetwear print',$ts],
        ['White Essential Tee','tshirt',599,null,null,'👕','Clean minimal design',$ts],
        ['Cargo Jogger Pants','pants',1499,1999,'SALE','👖','Utility + comfort',$ps],
        ['Slim Chino Pants','pants',1299,null,'NEW','👖','Smart casual fit',$ps],
        ['Track Pants','pants',999,1399,null,'👖','Sport & lounge wear',$ps],
        ['Polo Signature','polo',899,1199,'HOT','👔','Classic collar polo',$ts],
        ['Pique Polo','polo',799,null,null,'👔','Breathable fabric',$ts],
        ['Street Hoodie','hoodie',1799,2499,'SALE','🧥','Premium fleece hoodie',$ts],
        ['Zip-Up Hoodie','hoodie',1999,null,'NEW','🧥','Full zip comfort fit',$ts],
        ['Oversized Drop Tee','tshirt',749,1100,'NEW','👕','Trendy oversized cut',$ts],
        ['Formal Slim Pant','pants',1599,null,null,'👖','Office-ready slim fit',$ps],
    ];
    $stmt = $db->prepare("INSERT INTO products (name,cat,price,old_price,badge,emoji,description,images,sizes) VALUES (?,?,?,?,?,?,?,?,?)");
    foreach ($defaultProducts as $p) {
        $stmt->execute([$p[0],$p[1],$p[2],$p[3],$p[4],$p[5],$p[6],'[]',$p[7]]);
    }
}

$existCoup = $db->query("SELECT COUNT(*) as c FROM coupons")->fetch();
if ($existCoup['c'] == 0) {
    $db->exec("INSERT INTO coupons (code,type,value,min_items,active) VALUES ('ADAM2FREE','free_delivery',0,2,1),('ADAM10','percent',10,1,1)");
}

$existSett = $db->query("SELECT COUNT(*) as c FROM settings")->fetch();
if ($existSett['c'] == 0) {
    $now = time();
    $saleEnd = $now + 24*3600;
    $db->exec("INSERT INTO settings (`key`,`value`) VALUES
        ('admin_pass','adam2026'),
        ('whatsapp','01675760715'),
        ('store_name','ADAM Men\\'s Fashion'),
        ('sale_end','{$saleEnd}')
    ");
}

$existRev = $db->query("SELECT COUNT(*) as c FROM reviews")->fetch();
if ($existRev['c'] == 0) {
    $db->exec("INSERT INTO reviews (customer_name,product_name,rating,review_text,status) VALUES
        ('Rahim H.','Polo Signature',5,'Quality is amazing! Got my order in 2 days.','approved'),
        ('Karim S.','Cargo Jogger Pants',5,'Best fashion site in BD. bKash payment was smooth.','approved'),
        ('Nasir A.','Classic Black Tee',5,'Ordered 2 tees and got free delivery! Will order again.','approved'),
        ('Rafiq M.','Street Hoodie',4,'Good quality. WhatsApp support was very helpful.','pending')
    ");
}

$existCust = $db->query("SELECT COUNT(*) as c FROM customers")->fetch();
if ($existCust['c'] == 0) {
    $db->exec("INSERT INTO customers (name,phone,location,total_orders,total_spent) VALUES
        ('Rahim Hossain','01711000001','Dhaka',3,4200),
        ('Karim Sarkar','01712000002','Chittagong',1,1499),
        ('Nasir Ahmed','01813000003','Sylhet',2,3600),
        ('Sohel Rana','01914000004','Gazipur',1,1799),
        ('Jahed Khan','01615000005','Comilla',4,7800)
    ");
}

$existOrders = $db->query("SELECT COUNT(*) as c FROM orders")->fetch();
if ($existOrders['c'] == 0) {
    $db->exec("INSERT INTO orders (order_code,customer_name,customer_phone,customer_address,city,district,items,subtotal,discount,delivery_charge,total,payment_method,status) VALUES
        ('ORD001','Rahim Hossain','01711000001','Dhanmondi, Dhaka','Dhaka','Dhaka','[{\"name\":\"Classic Black Tee\",\"qty\":2,\"price\":699,\"size\":\"L\"}]',1398,0,80,1478,'bKash','done'),
        ('ORD002','Karim Sarkar','01712000002','Agrabad, Chittagong','Chittagong','Chittagong','[{\"name\":\"Cargo Jogger Pants\",\"qty\":1,\"price\":1499,\"size\":\"32\"}]',1499,0,130,1629,'Nagad','new'),
        ('ORD003','Nasir Ahmed','01813000003','Sylhet Sadar','Sylhet','Sylhet','[{\"name\":\"Polo Signature\",\"qty\":1,\"price\":899,\"size\":\"M\"}]',899,0,130,1029,'COD','pending')
    ");
}

if (empty($errors)) {
    echo '<div style="font-family:sans-serif;max-width:600px;margin:60px auto;background:#111;color:#f0ece4;padding:40px;border:1px solid #333;">';
    echo '<h1 style="color:#FF6B00;font-size:2rem;margin-bottom:20px;">✅ ADAM Database Installed!</h1>';
    echo '<p style="color:#888;margin-bottom:20px;">All tables created and seeded with default data.</p>';
    echo '<p style="color:#FF6B00;font-weight:bold;">⚠️ IMPORTANT: Delete this file (install.php) now for security!</p>';
    echo '<hr style="border-color:#333;margin:20px 0;">';
    echo '<p style="color:#888;font-size:.85rem;">Next steps:</p>';
    echo '<ol style="color:#888;font-size:.85rem;line-height:2;">';
    echo '<li>Delete <strong style="color:#f0ece4;">api/install.php</strong></li>';
    echo '<li>Visit your store: <strong style="color:#f0ece4;">yourdomain.com</strong></li>';
    echo '<li>Admin panel: <strong style="color:#f0ece4;">yourdomain.com/admin/</strong></li>';
    echo '<li>Login: <strong style="color:#f0ece4;">admin / adam2026</strong></li>';
    echo '</ol>';
    echo '</div>';
} else {
    echo '<pre style="background:#1a1a1a;color:#e74c3c;padding:20px;">Errors:<br>' . implode("\n", $errors) . '</pre>';
}
