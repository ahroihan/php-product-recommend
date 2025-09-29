<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$wishlist_items = getUserWishlist($user_id);

// Handle remove from wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_wishlist'])) {
    $product_id = $_POST['product_id'];
    if (removeFromWishlist($user_id, $product_id)) {
        header('Location: wishlist?message=Item removed from wishlist');
        exit();
    }
}
?>

<?php include 'includes/header.php'; ?>

<h1>❤️ Wishlist Saya</h1>

<?php if (isset($_GET['message'])): ?>
    <div class="message success"><?php echo htmlspecialchars($_GET['message']); ?></div>
<?php endif; ?>

<?php if (empty($wishlist_items)): ?>
    <div class="empty-state">
        <p>Wishlist Anda masih kosong.</p>
        <a href="<?php echo url('home'); ?>" class="btn btn-primary">Jelajahi Produk</a>
    </div>
<?php else: ?>
    <div class="wishlist-grid">
        <?php foreach ($wishlist_items as $item): ?>
        <div class="wishlist-item">
            <div class="item-image">
                <img src="<?php echo url('assets/images/products/' . $item['product_id'] . '.jpg'); ?>" 
                     alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                     onerror="this.src='<?php echo url('assets/images/placeholder.jpg'); ?>'">
            </div>
            
            <div class="item-details">
                <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                <p class="price">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></p>
                <p class="added-date">Ditambahkan: <?php echo date('d M Y', strtotime($item['added_date'])); ?></p>
            </div>
            
            <div class="item-actions">
                <a href="<?php echo url('product/' . $item['product_id']); ?>" class="btn btn-primary">
                    Lihat Detail
                </a>
                
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                    <button type="submit" name="remove_from_wishlist" class="btn btn-danger">
                        Hapus
                    </button>
                </form>
                
                <form method="POST" action="<?php echo url('log_behavior'); ?>" style="display: inline;">
                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                    <input type="hidden" name="behavior_type" value="cart_add">
                    <button type="submit" class="btn btn-secondary">
                        Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>