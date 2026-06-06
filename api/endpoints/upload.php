<?php
require_once dirname(__DIR__) . '/config.php';
requireAdmin();

if ($method !== 'POST') jsonError('Method not allowed', 405);

if (empty($_FILES['image'])) jsonError('No image file received');

$file     = $_FILES['image'];
$allowed_mime = ['image/jpeg','image/png','image/webp','image/gif'];
$mime     = mime_content_type($file['tmp_name']);

// Validate upload error first
if ($file['error'] !== UPLOAD_ERR_OK) {
    $errors = [1=>'File too large (server limit)',2=>'File too large (form limit)',3=>'Partial upload',4=>'No file uploaded'];
    jsonError('Upload error: ' . ($errors[$file['error']] ?? 'Unknown error #'.$file['error']));
}
if (!in_array($mime, $allowed_mime)) jsonError('Invalid file type. Only JPG, PNG, WEBP, GIF allowed.');
if ($file['size'] > MAX_FILE_SIZE) jsonError('File too large. Maximum size is 5MB.');
// Double-check extension is safe
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$safe_exts = ['jpg','jpeg','png','webp','gif'];
if (!in_array($ext, $safe_exts)) $ext = 'jpg';

// Create upload dir if missing
if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

// Unique filename
$filename = uniqid('prod_', true) . '.' . strtolower($ext);
$destPath = UPLOAD_DIR . $filename;

if (!move_uploaded_file($file['tmp_name'], $destPath)) jsonError('Failed to save file');

jsonSuccess([
    'filename' => $filename,
    'url'      => UPLOAD_URL . $filename,
    'full_url' => (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . UPLOAD_URL . $filename,
], 'Image uploaded');
