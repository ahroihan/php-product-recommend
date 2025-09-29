<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$cart_items = getCartItems($user_id);
$total_amount = 0;

foreach ($cart_items as $item) {
    $total_amount += $item['subtotal'];
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantities'] as $cart_id => $quantity) {
            updateCartItem($cart_id, $quantity);
        }
        header('Location: cart');
        exit();
    }
    
    if (isset($_POST['remove_item'])) {
        removeFromCart($_POST['cart_id']);
        header('Location: cart');
        exit();
    }
    
    if (isset($_POST['checkout'])) {
        $order_id = createOrder($user_id, $cart_items);
        if ($order_id) {
            header('Location: order_detail?id=' . $order_id);
            exit();
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<h1>🛒 Keranjang Belanja</h1>

<?php if (empty($cart_items)): ?>
    <div class="empty-cart">
        <p>Keranjang belanja Anda kosong.</p>
        <a href="<?php echo url('home'); ?>" class="btn btn-primary">Lanjutkan Belanja</a>
    </div>
<?php else: ?>
    <form method="POST" action="">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td>
                        <div class="cart-product-info">
                            <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                            <p class="category"><?php echo htmlspecialchars($item['category']); ?></p>
                        </div>
                    </td>
                    <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                    <td>
                        <input type="number" name="quantities[<?php echo $item['cart_id']; ?>]" 
                               value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                    </td>
                    <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                    <td>
                        <button type="submit" name="remove_item" class="btn btn-danger" 
                                onclick="return confirm('Hapus item dari keranjang?')">
                            ❌
                        </button>
                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td colspan="2"><strong>Rp <?php echo number_format($total_amount, 0, ',', '.'); ?></strong></td>
                </tr>
            </tfoot>
        </table>
        
        <div class="cart-actions">
            <button type="submit" name="update_cart" class="btn btn-secondary">Update Keranjang</button>
            <button type="submit" name="checkout" class="btn btn-primary">Checkout</button>
        </div>
    </form>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>