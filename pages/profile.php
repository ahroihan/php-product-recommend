<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$user_id = $_SESSION['user_id'];
$user_data = getUserData($user_id);
$user_behavior = getUserBehavior($user_id);
?>

<?php include 'includes/header.php'; ?>

<div class="profile-container">
    <h1>Profile User</h1>

    <div class="profile-info">
        <h2>Informasi Akun</h2>
        <p><strong>User ID:</strong> <?php echo $user_data['user_id']; ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user_data['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>
        <p><strong>Bergabung pada:</strong> <?php echo date('d M Y', strtotime($user_data['created_at'])); ?></p>
    </div>

    <div class="behavior-history">
        <h2>Riwayat Perilaku</h2>
        <?php if (!empty($user_behavior)): ?>
            <?php foreach ($user_behavior as $behavior): ?>
                <div class="behavior-item">
                    <p><strong>Produk:</strong> <?php echo htmlspecialchars($behavior['product_name']); ?></p>
                    <p><strong>Perilaku:</strong> <?php echo ucfirst($behavior['behavior_type']); ?></p>
                    <p><strong>Waktu:</strong> <?php echo date('d M Y H:i', strtotime($behavior['created_at'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada riwayat perilaku.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>