<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$status = $_GET['status'] ?? null;
$orders = getAdminOrders($status);
$message = '';

// Handle order actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;
    $admin_id = $_SESSION['user_id'];
    
    if (isset($_POST['approve_order']) && $order_id) {
        if (approveOrder($order_id, $admin_id)) {
            $message = 'Pesanan berhasil disetujui!';
            header('Location: orders?status=processing&message=' . urlencode($message));
            exit();
        }
    }
    
    if (isset($_POST['shipping_order']) && $order_id) {
        if (shippingOrder($order_id, $admin_id)) {
            $message = 'Pesanan berhasil disetujui!';
            header('Location: orders?status=processing&message=' . urlencode($message));
            exit();
        }
    }
    
    if (isset($_POST['reject_order']) && $order_id) {
        $reason = $_POST['rejection_reason'] ?? '';
        if (rejectOrder($order_id, $admin_id, $reason)) {
            $message = 'Pesanan berhasil ditolak!';
            header('Location: orders?status=processing&message=' . urlencode($message));
            exit();
        }
    }
    
    if (isset($_POST['update_status']) && $order_id) {
        $new_status = $_POST['new_status'] ?? '';
        if (updateOrderStatus($order_id, $new_status)) {
            $message = 'Status pesanan berhasil diupdate!';
            header('Location: orders?message=' . urlencode($message));
            exit();
        }
    }
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

// Get order counts for tabs
$pending_count = getOrdersByStatus('pending');
$processing_count = getOrdersByStatus('processing');
$shipped_count = getOrdersByStatus('shipped');
$paid_count = getOrdersByStatus('paid');
$all_count = $pending_count + $processing_count + $shipped_count + $paid_count;
?>

<?php include 'includes/header.php'; ?>

<h1>📦 Management Pesanan</h1>

<?php if ($message): ?>
    <div class="message success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<div class="admin-tabs">
    <a href="admin/orders" class="tab <?php echo !$status ? 'active' : ''; ?>">
        Semua Pesanan <span class="tab-badge"><?php echo $all_count; ?></span>
    </a>
    <a href="admin/orders?status=pending" class="tab <?php echo $status === 'pending' ? 'active' : ''; ?>">
        Pending <span class="tab-badge"><?php echo $pending_count; ?></span>
    </a>
    <a href="admin/orders?status=processing" class="tab <?php echo $status === 'processing' ? 'active' : ''; ?>">
        Perlu Approval <span class="tab-badge"><?php echo $processing_count; ?></span>
    </a>
    <a href="admin/orders?status=paid" class="tab <?php echo $status === 'paid' ? 'active' : ''; ?>">
        Paid <span class="tab-badge"><?php echo $paid_count; ?></span>
    </a>
    <a href="admin/orders?status=shipped" class="tab <?php echo $status === 'shipped' ? 'active' : ''; ?>">
        Shipped <span class="tab-badge"><?php echo $shipped_count; ?></span>
    </a>
</div>

<div class="admin-section">
    <h2>Daftar Pesanan <?php echo $status ? "($status)" : ''; ?></h2>
    
    <?php if (empty($orders)): ?>
        <p class="no-data">Tidak ada pesanan.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Items</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?php echo $order['order_id']; ?></td>
                    <td>
                        <div>
                            <strong><?php echo htmlspecialchars($order['customer_username']); ?></strong><br>
                            <small><?php echo htmlspecialchars($order['customer_email']); ?></small>
                        </div>
                    </td>
                    <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                    <td>
                        <span class="status-badge <?php echo $order['status']; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                    <td><?php echo $order['item_count']; ?> items</td>
                    <td class="action-buttons">
                        <a href="admin/order_details?id=<?php echo $order['order_id']; ?>" class="btn btn-primary">
                            Detail
                        </a>
                        
                        <?php if ($order['status'] === 'processing' && $order['payment_proof']): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" name="approve_order" class="btn btn-success" 
                                        onclick="return confirm('Setujui pesanan ini?')">
                                    ✅ Approve
                                </button>
                            </form>
                            
                            <button type="button" class="btn btn-danger" 
                                    onclick="showRejectForm(<?php echo $order['order_id']; ?>)">
                                ❌ Reject
                            </button>
                        <?php endif; ?>
                        <?php if ($order['status'] === 'paid' && $order['payment_proof']): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" name="shipping_order" class="btn btn-secondary" 
                                        onclick="return confirm('Kirim pesanan ini?')">
                                    🚚 Kirim
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                
                <!-- Reject Form (hidden) -->
                <tr id="reject-form-<?php echo $order['order_id']; ?>" style="display: none;">
                    <td colspan="7">
                        <form method="POST" class="reject-form">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <div class="form-group">
                                <label for="reason-<?php echo $order['order_id']; ?>">Alasan Penolakan:</label>
                                <textarea name="rejection_reason" id="reason-<?php echo $order['order_id']; ?>" 
                                          required placeholder="Masukkan alasan penolakan..." 
                                          class="form-control"></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit" name="reject_order" class="btn btn-danger">
                                    Konfirmasi Penolakan
                                </button>
                                <button type="button" class="btn btn-secondary" 
                                        onclick="hideRejectForm(<?php echo $order['order_id']; ?>)">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
function showRejectForm(orderId) {
    document.getElementById('reject-form-' + orderId).style.display = 'table-row';
}

function hideRejectForm(orderId) {
    document.getElementById('reject-form-' + orderId).style.display = 'none';
}
</script>

<?php include 'includes/footer.php'; ?>