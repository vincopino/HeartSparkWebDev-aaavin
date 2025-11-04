<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <i class="fas fa-bolt me-2 brand-bolt"></i>
      HeartSpark
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <!-- bloat logic is for showing active page, might need to rewrite shorter -->
        <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>" href="contact.php">Contact</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : '' ?>" href="cart.php">🛒 Cart
              <?php if (isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0): ?>
                <span class="badge bg-warning text-dark"><?php echo $_SESSION['cart_count']; ?></span>
              <?php endif; ?></a></li>
          <li class="nav-item"><a class="nav-link" href="server/logout.php">Log Out</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal"
              data-bs-target="#loginModal">Login/Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>