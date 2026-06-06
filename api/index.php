<?php
// ============================================================
//  ADAM Fashion — Main API Router
//  All API requests go through here
// ============================================================
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$path   = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts  = explode('/', $path);

// Find the endpoint after "api"
$apiIndex = array_search('api', $parts);
$endpoint = $parts[$apiIndex + 1] ?? '';
$id       = $parts[$apiIndex + 2] ?? null;

switch ($endpoint) {
    case 'products':   require 'endpoints/products.php'; break;
    case 'orders':     require 'endpoints/orders.php'; break;
    case 'customers':  require 'endpoints/customers.php'; break;
    case 'coupons':    require 'endpoints/coupons.php'; break;
    case 'reviews':    require 'endpoints/reviews.php'; break;
    case 'settings':   require 'endpoints/settings.php'; break;
    case 'auth':       require 'endpoints/auth.php'; break;
    case 'upload':     require 'endpoints/upload.php'; break;
    case 'stats':      require 'endpoints/stats.php'; break;
    default:           jsonError('Endpoint not found', 404);
}
