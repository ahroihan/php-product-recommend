<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$orders = getOrders($user_id);
?>

<?php include 'includes/header.php'; ?>

<h1>📋 Daftar Pesanan</h1>

<?php if (empty($orders)): ?>
    <div class="empty-orders">
        <p>Belum ada pesanan.</p>
        <a href="<?php echo url('home'); ?>" class="btn btn-primary">Mulai Belanja</a>
    </div>
<?php else: ?>
    <table class="orders-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Items</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?php echo $order['order_id']; ?></td>
                <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                <td><span class="status-badge <?php echo $order['status']; ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span></td>
                <td><?php echo $order['item_count']; ?> items</td>
                <td>
                    <a href="<?php echo url('order_detail?id=' . $order['order_id']); ?>" class="btn btn-primary">
                        Detail
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>