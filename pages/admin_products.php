<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$products = getAllProducts();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $product_name = $_POST['product_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];

        if (createProduct($product_name, $description, $price, $category)) {
            $message = 'Product created successfully!';
            header('Location: products?message=' . urlencode($message));
            exit();
        }
    }

    if (isset($_POST['delete_product'])) {
        $product_id = $_POST['product_id'];
        if (deleteProduct($product_id)) {
            $message = 'Product deleted successfully!';
            header('Location: products?message=' . urlencode($message));
            exit();
        }
    }

    if (isset($_POST['update_product'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (updateProduct($product_id, $product_name, $description, $price, $category, $is_active)) {
            $message = 'Product updated successfully!';
            header('Location: products?message=' . urlencode($message));
            exit();
        }
    }
}

if (isset($_GET['message'])) {
    $message = $_GET['message'];
}
?>

<?php include 'includes/header.php'; ?>

<h1>Manage Products</h1>

<?php if ($message): ?>
    <div class="message success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<div class="admin-section">
    <h2>Add New Product</h2>
    <form method="POST" class="admin-form">
        <input type="hidden" name="add_product" value="1">
        <div class="form-row">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="text" name="description" placeholder="Description" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="text" name="category" placeholder="Category" required>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </div>
    </form>
</div>

<div class="admin-section">
    <h2>Products List</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['product_id']; ?></td>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td>Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td><span class="status-badge <?php echo $product['is_active'] ? 'active' : 'inactive'; ?>">
                            <?php echo $product['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span></td>
                    <td class="action-buttons">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="hidden" name="delete_product" value="1">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>