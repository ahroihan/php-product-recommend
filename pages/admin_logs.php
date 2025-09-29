<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$logs = getAllBehaviorLogs(50);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_log'])) {
    $log_id = $_POST['log_id'];
    if (deleteBehaviorLog($log_id)) {
        $message = 'Log deleted successfully!';
        header('Location: logs.php?message=' . urlencode($message));
        exit();
    }
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>

<?php include 'includes/header.php'; ?>

<h1>User Behavior Logs</h1>

<?php if ($message): ?>
    <div class="message success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<div class="admin-section">
    <h2>Recent User Activities (Last 50)</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Log ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Behavior</th>
                <th>Timestamp</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo $log['log_id']; ?></td>
                    <td><?php echo htmlspecialchars($log['username']); ?></td>
                    <td><?php echo htmlspecialchars($log['product_name']); ?></td>
                    <td><span class="behavior-badge <?php echo $log['behavior_type']; ?>">
                            <?php echo ucfirst($log['behavior_type']); ?>
                        </span></td>
                    <td><?php echo date('M j, Y H:i', strtotime($log['created_at'])); ?></td>
                    <td class="action-buttons">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="log_id" value="<?php echo $log['log_id']; ?>">
                            <input type="hidden" name="delete_log" value="1">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this log?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>