<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: home');
    exit();
}

$product_id = $_GET['id'];
$product = getProductById($product_id);

if (!$product) {
    header('Location: home');
    exit();
}

// Log view behavior if user is logged in
if (isLoggedIn()) {
    logUserBehavior($_SESSION['user_id'], $product_id, 'view');
}
?>

<?php include 'includes/header.php'; ?>

<div class="product-detail">
    <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>

    <div class="product-info">
        <p><strong>Harga:</strong> Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
        <p><strong>Deskripsi:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
    </div>

    <?php if (isLoggedIn()): ?>
        <div class="product-actions">
            <form method="POST" action="log_behavior" style="display: inline;">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="behavior_type" value="cart_add">
                <button type="submit" class="btn btn-primary">🛒 Tambah ke Keranjang</button>
            </form>

            <form method="POST" action="log_behavior" style="display: inline;">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="behavior_type" value="wishlist">
                <button type="submit" class="btn btn-secondary">❤️ Wishlist</button>
            </form>
        </div>
    <?php else: ?>
        <p><a href="login">Login</a> untuk berinteraksi dengan produk ini.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>