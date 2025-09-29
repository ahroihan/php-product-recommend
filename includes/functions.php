<?php
require_once 'config/database.php';

// Fungsi login user
function loginUser($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $user['password'] === $password) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Tambah session role
        return true;
    }
    
    return false;
}

// Get user data
function getUserData($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get all products
function getAllProducts() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get user behavior
function getUserBehavior($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT p.*, ubl.behavior_type, ubl.created_at 
        FROM user_behavior_logs ubl 
        JOIN products p ON ubl.product_id = p.product_id 
        WHERE ubl.user_id = ? 
        ORDER BY ubl.created_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Log user behavior
function logUserBehavior($user_id, $product_id, $behavior_type) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        INSERT INTO user_behavior_logs (user_id, product_id, behavior_type, created_at) 
        VALUES (?, ?, ?, NOW())
    ");
    return $stmt->execute([$user_id, $product_id, $behavior_type]);
}

// Get recommended products for user
function getRecommendedProducts($user_id) {
    global $pdo;
    
    // Simple recommendation based on other users' behavior
    $stmt = $pdo->prepare("
        SELECT p.*, COUNT(*) as recommendation_score
        FROM user_behavior_logs ubl
        JOIN products p ON ubl.product_id = p.product_id
        WHERE ubl.user_id IN (
            SELECT DISTINCT user_id 
            FROM user_behavior_logs 
            WHERE product_id IN (
                SELECT product_id 
                FROM user_behavior_logs 
                WHERE user_id = ?
            ) AND user_id != ?
        )
        AND ubl.product_id NOT IN (
            SELECT product_id 
            FROM user_behavior_logs 
            WHERE user_id = ?
        )
        GROUP BY p.product_id
        ORDER BY recommendation_score DESC
        LIMIT 5
    ");
    $stmt->execute([$user_id, $user_id, $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get all users (for admin)
function getAllUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get user by ID
function getUserById($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function isUserExists($username, $email) {
    global $pdo;
    
    // Periksa keberadaan username atau email
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $count = $stmt->fetchColumn();

    return $count > 0;
}

// Create new user
function createUser($username, $password, $email, $role = 'user') {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$username, $password, $email, $role]);
}

// Update user
function updateUser($user_id, $username, $email, $role) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?");
    return $stmt->execute([$username, $email, $role, $user_id]);
}

// Delete user
function deleteUser($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    return $stmt->execute([$user_id]);
}

// Create product
function createProduct($product_name, $description, $price, $category) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO products (product_name, description, price, category) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$product_name, $description, $price, $category]);
}

// Update product
function updateProduct($product_id, $product_name, $description, $price, $category, $is_active) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE products SET product_name = ?, description = ?, price = ?, category = ?, is_active = ? WHERE product_id = ?");
    return $stmt->execute([$product_name, $description, $price, $category, $is_active, $product_id]);
}

// Delete product
function deleteProduct($product_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    return $stmt->execute([$product_id]);
}

// Get all behavior logs
function getAllBehaviorLogs($limit = 100) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT ubl.*, u.username, p.product_name 
        FROM user_behavior_logs ubl 
        JOIN users u ON ubl.user_id = u.user_id 
        JOIN products p ON ubl.product_id = p.product_id 
        ORDER BY ubl.created_at DESC 
        LIMIT ?
    ");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Delete behavior log
function deleteBehaviorLog($log_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM user_behavior_logs WHERE log_id = ?");
    return $stmt->execute([$log_id]);
}

// Get dashboard stats
function getDashboardStats() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM view_admin_dashboard");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Get user analytics
function getUserAnalytics() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM view_user_analytics");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// // Check if user is admin
// function isAdmin() {
//     return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
// }

// // Require admin access
// function requireAdmin() {
//     if (!isLoggedIn() || !isAdmin()) {
//         header('Location: home');
//         exit();
//     }
// }

// Get product by ID
function getProductById($product_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Cart functions
function getCartItems($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.*, p.product_name, p.price, p.category, 
               (c.quantity * p.price) as subtotal
        FROM carts c 
        JOIN products p ON c.product_id = p.product_id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCartCount($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT SUM(quantity) as total_count 
        FROM carts 
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_count'] ?? 0;
}

function addToCart($user_id, $product_id, $quantity = 1) {
    global $pdo;
    
    // Check if item already in cart
    $stmt = $pdo->prepare("SELECT * FROM carts WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Update quantity
        $stmt = $pdo->prepare("UPDATE carts SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$quantity, $user_id, $product_id]);
    } else {
        // Add new item
        $stmt = $pdo->prepare("INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $product_id, $quantity]);
    }
}

function updateCartItem($cart_id, $quantity) {
    global $pdo;
    if ($quantity <= 0) {
        return removeFromCart($cart_id);
    }
    $stmt = $pdo->prepare("UPDATE carts SET quantity = ? WHERE cart_id = ?");
    return $stmt->execute([$quantity, $cart_id]);
}

function removeFromCart($cart_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM carts WHERE cart_id = ?");
    return $stmt->execute([$cart_id]);
}

function clearCart($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
    return $stmt->execute([$user_id]);
}

// Order functions
function createOrder($user_id, $items, $payment_method = 'transfer') {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Calculate total
        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }
        
        // Create order
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total_amount, payment_method, status) 
            VALUES (?, ?, ?, 'pending')
        ");
        $stmt->execute([$user_id, $total, $payment_method]);
        $order_id = $pdo->lastInsertId();
        
        // Add order items
        foreach ($items as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $order_id, 
                $item['product_id'], 
                $item['quantity'], 
                $item['price'], 
                $item['subtotal']
            ]);
        }
        
        // Clear cart
        clearCart($user_id);
        
        $pdo->commit();
        return $order_id;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function getOrders($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT o.*, COUNT(oi.order_item_id) as item_count 
        FROM orders o 
        LEFT JOIN order_items oi ON o.order_id = oi.order_id 
        WHERE o.user_id = ? 
        GROUP BY o.order_id 
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderDetails($order_id) {
    global $pdo;
    
    // Get order
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) return null;
    
    // Get order items
    $stmt = $pdo->prepare("
        SELECT oi.*, p.product_name, p.category 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.product_id 
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'order' => $order,
        'items' => $items
    ];
}

function updatePaymentProof($order_id, $filename) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE orders SET payment_proof = ?, status = 'processing' WHERE order_id = ?");
    return $stmt->execute([$filename, $order_id]);
}

// Admin order functions
function getAdminOrders($status = null) {
    global $pdo;
    
    $sql = "SELECT * FROM view_admin_orders";
    if ($status) {
        $sql .= " WHERE status = ?";
    }
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    if ($status) {
        $stmt->execute([$status]);
    } else {
        $stmt->execute();
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderWithDetails($order_id) {
    global $pdo;
    
    // Get order with customer info
    $stmt = $pdo->prepare("
        SELECT o.*, u.username as customer_username, u.email as customer_email 
        FROM orders o 
        JOIN users u ON o.user_id = u.user_id 
        WHERE o.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) return null;
    
    // Get order items
    $stmt = $pdo->prepare("
        SELECT oi.*, p.product_name, p.category 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.product_id 
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'order' => $order,
        'items' => $items
    ];
}

function approveOrder($order_id, $admin_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE orders 
        SET status = 'paid', approved_by = ?, approved_at = NOW() 
        WHERE order_id = ?
    ");
    return $stmt->execute([$admin_id, $order_id]);
}

function shippingOrder($order_id, $admin_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE orders 
        SET status = 'shipped', approved_by = ?, approved_at = NOW() 
        WHERE order_id = ?
    ");
    return $stmt->execute([$admin_id, $order_id]);
}

function rejectOrder($order_id, $admin_id, $reason) {
    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE orders 
        SET status = 'cancelled', approved_by = ?, approved_at = NOW(), rejection_reason = ? 
        WHERE order_id = ?
    ");
    return $stmt->execute([$admin_id, $reason, $order_id]);
}

function updateOrderStatus($order_id, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    return $stmt->execute([$status, $order_id]);
}

function getOrdersByStatus($status) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM orders 
        WHERE status = ?
    ");
    $stmt->execute([$status]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

function getUserWishlist($user_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT p.*, ubl.created_at as added_date
                          FROM user_behavior_logs ubl
                          JOIN products p ON ubl.product_id = p.product_id
                          WHERE ubl.user_id = ? AND ubl.behavior_type = 'wishlist'
                          ORDER BY ubl.created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>