<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if (!isset($_GET['id'])) {
    header('Location: orders');
    exit();
}

$order_id = $_GET['id'];
$order_data = getOrderDetails($order_id);
$user_id = $_SESSION['user_id'];

// Check if order belongs to user
if (!$order_data || $order_data['order']['user_id'] != $user_id) {
    header('Location: orders');
    exit();
}

$order = $order_data['order'];
$items = $order_data['items'];

// Handle payment proof upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['payment_proof'])) {
    $upload_dir = __DIR__ . '/../uploads/payments/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $filename = 'payment_' . $order_id . '_' . time() . '_' . basename($_FILES['payment_proof']['name']);
    $target_file = $upload_dir . $filename;
    
    if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_file)) {
        updatePaymentProof($order_id, $filename);
        header('Location: order_detail?id=' . $order_id);
        exit();
    }
}
?>

<?php include 'includes/header.php'; ?>

<h1>📦 Detail Pesanan #<?php echo $order_id; ?></h1>

<div class="order-status">
    <p><strong>Status:</strong> <span class="status-badge <?php echo $order['status']; ?>">
        <?php echo ucfirst($order['status']); ?>
    </span></p>
    <p><strong>Tanggal:</strong> <?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></p>
    <p><strong>Total:</strong> Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></p>
</div>

<h2>Items Pesanan</h2>
<table class="order-table">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
            <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($order['status'] == 'pending' && empty($order['payment_proof'])): ?>
<div class="payment-section">
    <h2>Upload Bukti Pembayaran</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="payment_proof">Upload Bukti Transfer:</label>
            <input type="file" name="payment_proof" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload Bukti</button>
    </form>
    
    <div class="payment-instruction">
        <h3>Instruksi Pembayaran:</h3>
        <p>1. Transfer ke rekening: <strong>BRI 1234-5678-9012 (ShopReco)</strong></p>
        <p>2. Jumlah: <strong>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></strong></p>
        <p>3. Upload bukti transfer di atas</p>
    </div>
</div>
<?php elseif ($order['payment_proof']): ?>
<div class="payment-proof">
    <h2>Bukti Pembayaran</h2>
    <p>Bukti pembayaran sudah diupload. Menunggu verifikasi admin.</p>
    <img src="<?php echo url('uploads/payments/' . $order['payment_proof']); ?>" 
         alt="Bukti Pembayaran" style="max-width: 300px; border: 1px solid #ddd;">
</div>
<?php endif; ?>

<div class="order-actions">
    <a href="<?php echo url('orders'); ?>" class="btn btn-secondary">Kembali ke Daftar Pesanan</a>
</div>

<?php include 'includes/footer.php'; ?>