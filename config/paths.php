<?php
// Define application paths
if (!defined('APP_ROOT')) {
    // Get the base URL correctly
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script_path = dirname($_SERVER['SCRIPT_NAME']);
    
    // Remove any duplicate slashes and ensure proper formatting
    $base_url = rtrim($protocol . '://' . $host . $script_path, '/');
    
    define('APP_ROOT', realpath(dirname(__FILE__) . '/..'));
    define('APP_URL', $base_url);
}

// Helper function untuk generate absolute URLs
function url($path = '') {
    $path = ltrim($path, '/');
    return $path ? APP_URL . '/' . $path : APP_URL;
}
// function url($path = '') {
//     // Basic absolute URL generation
//     $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
//     $host = $_SERVER['HTTP_HOST'];
    
//     // Remove the filename from script path
//     $script_path = dirname($_SERVER['SCRIPT_NAME']);
//     $script_path = rtrim($script_path, '/');
    
//     $base_url = $protocol . '://' . $host . $script_path;
//     $path = ltrim($path, '/');
    
//     return $path ? $base_url . '/' . $path : $base_url;
// }

// Helper function untuk assets (CSS, JS, images)
function asset($file) {
    return url($file);
}

// Get current URL path
function current_path() {
    $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $base_path = parse_url(APP_URL, PHP_URL_PATH);
    
    // Remove base path from request URI
    $path = str_replace($base_path, '', $request_uri);
    return trim($path, '/');
}

// Debug function untuk melihat URL
function debug_url($path = '') {
    echo "URL for '$path': " . url($path) . "<br>";
}
?>