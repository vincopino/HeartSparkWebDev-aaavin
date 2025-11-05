<?php
require_once 'database/config.php'; // provides session start and Database class (wrapper around PDO)
$pageTitle = "Products - HeartSpark";
?>

<!DOCTYPE html>
<html lang="en">

<?php require 'partials/head.php'; ?>


<?php

$db = new Database();
$pdo = $db->getConnection();

// Fetch products into $products array.\
$products = [];
$dbError = null;
try {
  $products = $db->fetchAll('SELECT * FROM products ORDER BY id ASC');
} catch (Exception $e) {
  $dbError = 'Error fetching products: ' . $e->getMessage();
}
?>

<body>
  <!-- NAVBAR -->
  <?php require 'partials/navbar.php'; ?>

  <!-- Login/Register Modals -->
  <?php require 'partials/modals.php'; ?>

  <script src="scripts/details.js"></script>

  <header class="text-center text-white py-5 page-header">

    <div id="cart-message"></div>

    <div class="container">
      <h1 class="display-4">Our Products</h1>
      <p class="lead">Browse our exclusive collection</p>
    </div>
  </header>
  <section class="py-5">
    <div class="container">

      <div class="row g-4" id="productList">
        <?php if ($dbError): ?>
          <div class="col-12">
            <div class="alert alert-warning"><?= htmlspecialchars($dbError) ?></div>
          </div>
        <?php endif; ?>

        <?php if (empty($products)): ?>
          <?php if (!$dbError): ?>
            <div class="col-12">
              <p class="text-center">No products found.</p>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <?php foreach ($products as $product):
            $id = htmlspecialchars($product['id']);
            $name = htmlspecialchars($product['item_name']);
            $desc = $product['item_desc'] ?? '';
            $priceCents = isset($product['price_cents']) ? (int)$product['price_cents'] : 0;
            $price = '$' . number_format($priceCents / 100, 2);
            $image = !empty($product['image_path']) ? $product['image_path'] : 'images/placeholder.png';
          ?>
            <div class="col-md-4" data-name="<?= strtolower(htmlspecialchars($product['item_name'])) ?>">
              <div class="card h-100">
                <img src="<?= htmlspecialchars($image) ?>" class="card-img-top" alt="<?= $name ?>">
                <div class="card-body">
                  <h5 class="card-title"><?= $name ?></h5>
                  <p class="card-text"><?= htmlspecialchars($price) ?></p>
                  <button class="btn btn-outline-info w-100 mb-2 btn-view"
                          data-name="<?= htmlspecialchars($product['item_name']) ?>"
                          data-desc="<?= htmlspecialchars($desc) ?>"
                          data-price="<?= htmlspecialchars($price) ?>"
                          >View Details</button>
                          <!-- Add To Cart is disabled when logged out -->
                  <button class="btn btn-primary w-100 btn-add <?= (isset($_SESSION['user_id'])) ? '' : 'disabled' ?>" 
                          data-product-id="<?= $product['id'] ?>"
                          data-name="<?= htmlspecialchars($product['item_name']) ?>"
                          data-price="<?= htmlspecialchars($price) ?>"
                          data-image="<?= htmlspecialchars($image) ?>"
                          ><?= (isset($_SESSION['user_id'])) ? 'Add to Cart' : 'Log In to Purchase' ?></button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Product Details Modal -->
  <div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-light" id="productDetailsModalLabel">Product Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="productDetailsBody">
        </div>
      </div>

    </div>
  </div>


</body>

</html>