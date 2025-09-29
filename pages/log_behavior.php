<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'] ?? null;
    $behavior_type = $_POST['behavior_type'] ?? null;
    
    if ($product_id && $behavior_type) {
        if ($behavior_type === 'cart_add') {
            addToCart($user_id, $product_id, 1);
        }
        
        logUserBehavior($user_id, $product_id, $behavior_type);
    }
}

// Redirect back
$redirect_url = $_SERVER['HTTP_REFERER'] ?? url('home');
header('Location: ' . $redirect_url);
exit();
?>