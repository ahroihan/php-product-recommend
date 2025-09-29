<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$stats = getDashboardStats();
$users = getUserAnalytics();
?>

<?php include 'includes/header.php'; ?>

<h1>Admin Dashboard</h1>

<div class="dashboard-stats">
    <div class="stat-card">
        <h3>Total Users</h3>
        <p class="stat-number"><?php echo $stats['total_users']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Products</h3>
        <p class="stat-number"><?php echo $stats['total_products']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Today Interactions</h3>
        <p class="stat-number"><?php echo $stats['today_interactions']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Purchases</h3>
        <p class="stat-number"><?php echo $stats['total_purchases']; ?></p>
    </div>
</div>

<div class="admin-section">
    <h2>Quick Actions</h2>
    <div class="action-buttons">
        <a href="admin/users" class="btn btn-primary">Manage Users</a>
        <a href="admin/products" class="btn btn-primary">Manage Products</a>
        <a href="admin/orders" class="btn btn-primary">Manage Orders</a>
        <a href="admin/logs" class="btn btn-primary">View Logs</a>
        <a href="admin/analytics" class="btn btn-secondary">User Analytics</a>
    </div>
</div>

<div class="recent-activity">
    <h2>User Analytics</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Total Interactions</th>
                <th>Total Purchases</th>
                <th>Last Activity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><span class="role-badge <?php echo $user['role']; ?>"><?php echo $user['role']; ?></span></td>
                    <td><?php echo $user['total_interactions']; ?></td>
                    <td><?php echo $user['total_purchases']; ?></td>
                    <td><?php echo $user['last_activity'] ? date('M j, Y', strtotime($user['last_activity'])) : 'Never'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>