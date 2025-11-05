<?php 

require_once '../database/config.php';

header('Content-Type: application/json');

// Check product_id
if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid product ID'
    ]);
    exit();
}

// Instantiate database and session data
$db = new Database();
$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];

// find product details
$product = $db->fetchOne('SELECT * FROM products WHERE id = ?', [$product_id]);
$price_cents = (int)$product['price_cents'];

if (!$product) {
    echo json_encode([
        'success' => false,
        'message' => 'Product not found'
    ]);
    exit();
}

// Check if item already in cart
$existing_item = $db->fetchOne(
    'SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?',
    [$user_id, $product_id]
);

if ($existing_item) {
    // Update quantity
    $db->query(
        'UPDATE cart_items SET quantity = quantity + 1 WHERE id = ?',
        [$existing_item['id']]
    );
} else {
    // Add new item to cart
    $db->query(
        'INSERT INTO cart_items (user_id, product_id, unit_price_cents,quantity) VALUES (?, ?, ?, 1)',
        [$user_id, $product_id, $price_cents]
    );
}

// Get updated cart count
$cart_count = $db->fetchOne(
    'SELECT COUNT(*) as count FROM cart_items WHERE user_id = ?',
    [$user_id]
);

// Update session cart count
$_SESSION['cart_count'] = $cart_count['count'];

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Product added to cart successfully',
    'cart_count' => $cart_count['count']
]);

?>