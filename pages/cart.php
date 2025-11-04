<?php
require_once 'database/config.php'; // provides session start and Database class (wrapper around PDO)
$pageTitle = "Cart - HeartSpark";

$db = new Database();

// For removing items from cart
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
  $cart_id = $_GET['remove'];
  $db->query(
    'DELETE FROM cart_items WHERE id = ? AND user_id = ?',
    [$cart_id, $_SESSION['user_id']]
  );

  header('Location: cart.php?success=Item removed from cart');
  exit();
}

// get cart items and product details
$cart_items = $db->fetchAll('
  SELECT 
    cart_items.id As cart_id,
    cart_items.quantity,
    (cart_items.unit_price_cents / 100.0) AS unit_price,
    products.id AS product_id,
    products.item_name,
    products.image_path,
    (cart_items.unit_price_cents * cart_items.quantity) / 100.0 AS total_unit_price
  FROM cart_items
  JOIN products ON cart_items.product_id = products.id
  WHERE cart_items.user_id = ?
', [$_SESSION['user_id']]);

// calculate total and convert into dollars
$total_price = 0;
foreach ($cart_items as $item) {
  $total_price += $item['total_unit_price'];
}

// get success message
$success = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';

?>


<!DOCTYPE html>
<html lang="en">

<?php require 'partials/head.php'; ?>

<body>
  <!-- NAVBAR -->
  <?php require 'partials/navbar.php'; ?>

  <!-- Login/Register Modals -->
  <?php require 'partials/modals.php'; ?>


  <header class="text-center text-white py-5 page-header">
    <div class="container">
      <h1 class="display-4">Your Cart</h1>
    </div>
  </header>


  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <?php if ($success): ?>
          <div class="alert alert-success alert-dismissible fade show">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        <div class="col-md-8">
          <div class="card shadow">
            <div class="card-body">
              <h2 class="card-title mb-3">Your Cart</h2>

              <div id="cartContent"></div>
              <?php if (empty($cart_items)): ?>

                <div class="alert alert-info">
                  <h4>Your cart is empty</h4>
                  <p>Start shopping and add items to your cart!</p>
                  <a href="products.php" class="btn btn-neon">Browse Products</a>
                </div>

              <?php else: ?>

                <div class="row">
                  <div class="col-md-8">
                    <div class="card shadow-sm">
                      <div class="card-body">

                        <table class="table">
                          <thead>
                            <tr>
                              <th>Product</th>
                              <th>Price</th>
                              <th>Quantity</th>
                              <th>Subtotal</th>
                              <th>Action</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php foreach ($cart_items as $item): ?>
                              <tr>
                                <td>
                                  <div class="d-flex align-items-center">
                                    <img src="<?php echo htmlspecialchars($item['image_path']); ?>"
                                      alt="<?php echo htmlspecialchars($item['item_name']); ?>"
                                      style="width: 60px; height: 60px; object-fit: cover;"
                                      class="me-3 rounded">
                                    <strong><?php echo htmlspecialchars($item['item_name']); ?></strong>
                                  </div>
                                </td>

                                <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><strong>$<?php echo number_format($item['total_unit_price'], 2); ?></strong></td>
                                <td>
                                  <a href="cart.php?remove=<?php echo $item['cart_id']; ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Remove this item from cart?')">
                                    Remove
                                  </a>
                                </td>

                              </tr>
                            <?php endforeach; ?>
                          </tbody>

                        </table>

                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="card shadow-sm">
                      <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Cart Summary</h5>
                      </div>
                      <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                          <span>Items:</span>
                          <strong><?php echo count($cart_items); ?></strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                          <h5>Total:</h5>
                          <h5 class="text-primary">$<?php echo number_format($total_price, 2); ?></h5>
                        </div>
                        <!-- BUTTON CURRENTLY DISABLED -->
                        <button class="btn btn-success w-100 mb-2 disabled">Proceed to Checkout</button>
                        <a href="products.php" class="btn btn-outline-primary w-100">Continue Shopping</a>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
                </div>
            </div>
          </div>
        </div>
      </div>
  </section>


</body>


</html>