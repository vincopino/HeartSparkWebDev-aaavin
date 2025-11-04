<?php
require_once 'database/config.php'; // provides session start and Database class (wrapper around PDO)
$pageTitle = "About - HeartSpark";
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
      <h1 class="display-4">About Us</h1>
    </div>
  </header>

  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center mb-4">
        <div class="col-md-8">
          <div class="card shadow">
            <div class="card-body">
              <h2 class="card-title mb-3">Welcome to HeartSpark!</h2>
              <p class="card-text">We are dedicated to providing premium and high-end products for your personal needs
                and lifestyle. Our mission is to offer a unique shopping experience with a curated selection of
                exclusive items. Thank you for visiting our website!</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center mb-4">
        <div class="col-md-8">
          <div class="card shadow">
            <div class="card-body">
              <h3 class="card-title mb-2">Our Mission</h3>
              <p class="card-text">To deliver quality, exclusivity, and satisfaction to every customer through our
                curated product selection and excellent service.</p>
              <h3 class="card-title mb-2 mt-4">Our Vision</h3>
              <p class="card-text">To be the leading online destination for unique and high-end products, recognized for
                innovation and customer care.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card shadow">
            <div class="card-body">
              <h3 class="card-title mb-3">Meet the Team</h3>
              <div class="row">
                <div class="col-md-6 text-center mb-3">
                  <img src="images/dp cat mafia 2.jpg" class="rounded-circle mb-2" width="100" height="100"
                    alt="Team Member 1">
                  <h5>Irish Arabaca</h5>
                  <p class="text-muted">Co-Founder & CEO</p>
                </div>
                <div class="col-md-6 text-center mb-3">
                  <img src="images/dp cat mafia 1.jpg" class="rounded-circle mb-2" width="100" height="100"
                    alt="Team Member 2">
                  <h5>David Vallejera</h5>
                  <p class="text-muted">Co-Founder & CTO</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


</body>


</html>