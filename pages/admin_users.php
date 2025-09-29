<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$users = getAllUsers();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        if (deleteUser($user_id)) {
            $message = 'User deleted successfully!';
            header('Location: users?message=' . urlencode($message));
            exit();
        }
    }

    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        if (empty($username) || empty($password) || empty($email)) {
            $message = 'Username, password, dan email tidak boleh kosong.';
            header('Location: users?message=' . urlencode($message));
            exit();
        }

        if (isUserExists($username, $email)) {
            $message = 'Username atau email sudah digunakan.';
            header('Location: users?message=' . urlencode($message));
            exit();
        }

        if (createUser($username, $password, $email, $role)) {
            $message = 'User created successfully!';
            header('Location: users?message=' . urlencode($message));
            exit();
        }
    }

    if (isset($_POST['edit_user'])) {
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        if (updateUser($user_id, $username, $email, $role)) {
            $message = 'User updated successfully!';
            header('Location: users?message=' . urlencode($message));
            exit();
        }
    }
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>

<?php include 'includes/header.php'; ?>

<h1>Manage Users</h1>

<?php if ($message): ?>
    <div class="message success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<div class="admin-section">
    <h2>Add New User</h2>
    <form method="POST" class="admin-form">
        <input type="hidden" name="add_user" value="1">
        <div class="form-row">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" class="btn btn-primary">Add User</button>
        </div>
    </form>
</div>

<div class="admin-section">
    <h2>Users List</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><span class="role-badge <?php echo $user['role']; ?>"><?php echo $user['role']; ?></span></td>
                    <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                    <td class="action-buttons">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="hidden" name="delete_user" value="1">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>