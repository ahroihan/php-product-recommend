<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$user_analytics = getUserAnalytics();
$behavior_stats = getDashboardStats();
?>

<?php include 'includes/header.php'; ?>

<h1>User Analytics</h1>

<div class="admin-section">
    <h2>User Behavior Statistics</h2>
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Users</h3>
            <p class="stat-number"><?php echo $behavior_stats['total_users']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Interactions</h3>
            <p class="stat-number"><?php echo $behavior_stats['today_interactions'] + 125; // Contoh 
                                    ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Purchases</h3>
            <p class="stat-number"><?php echo $behavior_stats['total_purchases']; ?></p>
        </div>
        <div class="stat-card">
            <h3>Active Products</h3>
            <p class="stat-number"><?php echo $behavior_stats['total_products']; ?></p>
        </div>
    </div>
</div>

<div class="admin-section">
    <h2>User Activity Overview</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Total Interactions</th>
                <th>Purchases</th>
                <th>Last Activity</th>
                <th>Activity Score</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user_analytics as $user): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><span class="role-badge <?php echo $user['role']; ?>"><?php echo $user['role']; ?></span></td>
                    <td><?php echo $user['total_interactions']; ?></td>
                    <td><?php echo $user['total_purchases']; ?></td>
                    <td><?php echo $user['last_activity'] ? date('M j, Y', strtotime($user['last_activity'])) : 'Never'; ?></td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?php echo min($user['total_interactions'] * 10, 100); ?>%">
                                <?php echo $user['total_interactions']; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>