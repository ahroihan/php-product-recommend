<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

if (!isset($_GET['id'])) {
    header('Location: orders');
    exit();
}

$order_id = $_GET['id'];
$order_data = getOrderWithDetails($order_id);

if (!$order_data) {
    header('Location: orders');
    exit();
}

$order = $order_data['order'];
$items = $order_data['items'];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $new_status = $_POST['status'];
        if (updateOrderStatus($order_id, $new_status)) {
            header('Location: order_details?id=' . $order_id . '&message=Status+updated');
            exit();
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<h1>📦 Detail Pesanan #<?php echo $order_id; ?></h1>

<div class="order-detail-admin">
    <div class="order-info">
        <h2>Informasi Pesanan</h2>
        <div class="info-grid">
            <div class="info-item">
                <strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_username']); ?>
            </div>
            <div class="info-item">
                <strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?>
            </div>
            <div class="info-item">
                <strong>Total Amount:</strong> Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>
            </div>
            <div class="info-item">
                <strong>Status:</strong> 
                <span class="status-badge <?php echo $order['status']; ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </div>
            <div class="info-item">
                <strong>Tanggal Pesan:</strong> <?php echo date('d M Y H:i', strtotime($order['created_at'])); ?>
            </div>
            <?php if ($order['approved_by']): ?>
            <div class="info-item">
                <strong>Disetujui oleh:</strong> <?php echo $order['approver_username']; ?>
            </div>
            <div class="info-item">
                <strong>Tanggal Approval:</strong> <?php echo date('d M Y H:i', strtotime($order['approved_at'])); ?>
            </div>
            <?php endif; ?>
            <?php if ($order['rejection_reason']): ?>
            <div class="info-item">
                <strong>Alasan Penolakan:</strong> <?php echo htmlspecialchars($order['rejection_reason']); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($order['payment_proof']): ?>
    <div class="payment-proof-section">
        <h2>Bukti Pembayaran</h2>
        <div class="payment-proof">
            <img src="<?php echo url('uploads/payments/' . $order['payment_proof']); ?>" 
                 alt="Bukti Pembayaran" class="proof-image">
            <div class="proof-actions">
                <?php if ($order['status'] === 'processing'): ?>
                <form method="POST" action="orders" style="display: inline;">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <button type="submit" name="approve_order" class="btn btn-success">
                        ✅ Approve Pembayaran
                    </button>
                </form>
                
                <button type="button" class="btn btn-danger" onclick="showRejectForm()">
                    ❌ Reject Pembayaran
                </button>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($order['status'] === 'processing'): ?>
        <div id="reject-form" style="display: none; margin-top: 1rem;">
            <form method="POST" action="admin/orders" class="reject-form">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                <div class="form-group">
                    <label for="rejection_reason">Alasan Penolakan:</label>
                    <textarea name="rejection_reason" id="rejection_reason" required 
                              placeholder="Masukkan alasan penolakan pembayaran..." 
                              class="form-control"></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" name="reject_order" class="btn btn-danger">
                        Konfirmasi Penolakan
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="hideRejectForm()">
                        Batal
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="order-items">
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
                    <td>
                        <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                        <br><small><?php echo htmlspecialchars($item['category']); ?></small>
                    </td>
                    <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td><strong>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="status-update">
        <h2>Update Status</h2>
        <form method="POST">
            <div class="form-group">
                <select name="status" class="form-control">
                    <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                    <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                    <option value="paid" <?php echo $order['status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                    <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
            <a href="admin/orders" class="btn btn-danger">Kembali</a>
        </form>
    </div>
</div>

<script>
function showRejectForm() {
    document.getElementById('reject-form').style.display = 'block';
}

function hideRejectForm() {
    document.getElementById('reject-form').style.display = 'none';
}
</script>

<?php include 'includes/footer.php'; ?>