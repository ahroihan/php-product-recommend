<?php 
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include paths config
require_once __DIR__ . '/../config/paths.php';
require_once __DIR__ . '/auth.php';

// Get cart count if user is logged in
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/functions.php';
    $cart_count = getCartCount($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rekomendasi Produk</title>
    <link rel="stylesheet" href="<?php echo asset('style.css'); ?>">
    <base href="<?php echo APP_URL . '/'; ?>">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-brand">
                <h1>🛍️ ShopReco</h1>
            </div>
            <div class="nav-menu">
                <a href="./" class="nav-link">Home</a>
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="admin" class="nav-link">Admin</a>
                    <?php endif; ?>
                    <a href="profile" class="nav-link">Profile</a>
                    <a href="<?php echo url('orders'); ?>" class="nav-link">Order</a>
                    <a href="logout" class="nav-link">Logout</a>
                    <span class="user-welcome">Halo, <?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['role']; ?>)</span>
                    <a href="<?php echo url('cart'); ?>" class="nav-link cart-link">
                        🛒
                        <?php
                        $cart_count = getCartCount($_SESSION['user_id']);
                        if ($cart_count > 0): ?>
                            <span class="cart-badge"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="<?php echo url('wishlist'); ?>" class="nav-link cart-link">
                        🤍
                        <?php
                        $wishlist_count = count(getUserWishlist($_SESSION['user_id']));
                        if ($wishlist_count > 0): ?>
                            <span class="cart-badge"><?php echo $wishlist_count; ?></span>
                        <?php endif; ?>
                    </a>
                <?php else: ?>
                    <a href="login" class="nav-link">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container">