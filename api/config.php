<?php
// ============================================================
//  ADAM Fashion — Database Configuration
//  Edit these values with your Xeon BD MySQL details
// ============================================================

define('DB_HOST', 'localhost');        // Usually localhost on Xeon BD
define('DB_NAME', 'your_db_name');     // Your MySQL database name from cPanel
define('DB_USER', 'your_db_user');     // Your MySQL username from cPanel
define('DB_PASS', 'your_db_password'); // Your MySQL password from cPanel
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME', 'ADAM Men\'s Fashion');
define('WHATSAPP_NUMBER', '8801675760715');
define('ADMIN_USERNAME', 'admin');
define('UPLOAD_DIR', __DIR__ . '/../uploads/products/');
// Dynamic upload URL — works in root or subfolder install
define('UPLOAD_URL', (function(){
    $script = $_SERVER['SCRIPT_NAME'] ?? '/api/index.php';
    // Go up from /api/index.php or /api/endpoints/upload.php to site root
    $root = rtrim(dirname(dirname($script)), '/');
    return $root . '/uploads/products/';
})());
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// ============================================================
//  DATABASE CONNECTION
// ============================================================
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            jsonError('Database connection failed: ' . $e->getMessage(), 500);
        }
    }
    return $pdo;
}

// ============================================================
//  HELPERS
// ============================================================
function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    echo json_encode($data);
    exit;
}

function jsonError($msg, $code = 400) {
    jsonResponse(['success' => false, 'error' => $msg], $code);
}

function jsonSuccess($data = [], $msg = 'OK') {
    jsonResponse(['success' => true, 'message' => $msg, 'data' => $data]);
}

function getInput() {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}

function sanitize($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

// Session-based admin auth
function isAdmin() {
    if(session_status()===PHP_SESSION_NONE) session_start();
    return isset($_SESSION['adam_admin']) && $_SESSION['adam_admin'] === true;
}

function requireAdmin() {
    if (!isAdmin()) jsonError('Unauthorized', 401);
}

// Handle OPTIONS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}
