<?php
/**
 * index.php - Main Router
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include paths config
require_once 'config/paths.php';

// Get the requested path
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base_path = parse_url(APP_URL, PHP_URL_PATH);

// Remove base path from request URI
$path = str_replace($base_path, '', $request_uri);
$path = trim($path, '/');

// Jika path kosong, set ke home
if (empty($path)) {
    $path = 'home';
}

// Define routes
$routes = [
    'home' => 'pages/home.php',
    'login' => 'pages/login.php',
    'logout' => 'pages/logout.php',
    'profile' => 'pages/profile.php',
    'cart' => 'pages/cart.php',
    'orders' => 'pages/orders.php',
    'order_detail' => 'pages/order_detail.php',
    'admin' => 'pages/admin_dashboard.php',
    'admin/users' => 'pages/admin_users.php',
    'admin/products' => 'pages/admin_products.php',
    'admin/orders' => 'pages/admin_orders.php',
    'admin/order_details' => 'pages/admin_order_detail.php',
    'admin/logs' => 'pages/admin_logs.php',
    'admin/analytics' => 'pages/admin_analytics.php',
    'log_behavior' => 'pages/log_behavior.php'
];

// Handle product detail routes (product/123)
if (preg_match('#^product/(\d+)$#', $path, $matches)) {
    $_GET['id'] = $matches[1];
    $path = 'product';
    $routes['product'] = 'pages/product_detail.php';
}

// Handle order detail routes (order_detail/123)
if (preg_match('#^order_detail/(\d+)$#', $path, $matches)) {
    $_GET['id'] = $matches[1];
    $path = 'order_detail';
}

// Find the matching route
$target_file = $routes[$path] ?? null;

// Jika route tidak ditemukan, coba file static
if (!$target_file) {
    // Serve static files (CSS, JS, images)
    if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|webp)$/i', $path)) {
        $static_file = APP_ROOT . '/' . $path;
        if (file_exists($static_file)) {
            $mime_types = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'ico' => 'image/x-icon',
                'webp' => 'image/webp'
            ];
            
            $extension = strtolower(pathinfo($static_file, PATHINFO_EXTENSION));
            if (isset($mime_types[$extension])) {
                header('Content-Type: ' . $mime_types[$extension]);
                readfile($static_file);
                exit;
            }
        }
    }
    
    // 404 Not Found
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Halaman Tidak Ditemukan</h1>";
    echo "<p>Halaman '$path' tidak ditemukan.</p>";
    echo "<p><a href='" . url('home') . "'>Kembali ke Home</a></p>";
    exit;
}

// Include the target file
if (file_exists($target_file)) {
    require_once $target_file;
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 File Tidak Ditemukan</h1>";
    echo "<p>File '$target_file' tidak ditemukan.</p>";
}
?>