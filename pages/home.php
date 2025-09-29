<?php
// Include config dan functions dengan path yang benar
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$products = getAllProducts();

if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $recommended_products = getRecommendedProducts($user_id);
} else {
    $recommended_products = [];
}
?>

<?php include 'includes/header.php'; ?>

<h1><?php echo isLoggedIn() ? 'Selamat Datang, ' . $_SESSION['username'] . '!' : 'Produk Kami'; ?></h1>

<?php if (isLoggedIn() && !empty($recommended_products)): ?>
    <section class="recommended-section">
        <h2>Rekomendasi untuk Anda</h2>
        <div class="products-grid">
            <?php foreach ($recommended_products as $product): ?>
                <div class="product-card">
                    <h3 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <p class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                    <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
                    <div class="product-actions">
                        <a href="product/<?php echo $product['product_id']; ?>" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<section class="all-products">
    <h2><?php echo isLoggedIn() ? 'Semua Produk' : 'Daftar Produk'; ?></h2>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h3 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                <p class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
                <div class="product-actions">
                    <a href="product/<?php echo $product['product_id']; ?>" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>