<?php
require_once 'database/config.php'; // provides session start and Database class (wrapper around PDO)
$pageTitle = "Contact - HeartSpark";
?>

<!DOCTYPE html>
<html lang="en">

<?php require 'partials/head.php'; ?>
<?php 

// Instantiate the new Database wrapper and obtain a PDO connection
$db = new Database();
$pdo = $db->getConnection();

// Simple, readable server-side form handling with validation
$formFeedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Trim inputs
  $name = isset($_POST['name']) ? trim((string)$_POST['name']) : '';
  $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
  $message = isset($_POST['message']) ? trim((string)$_POST['message']) : '';

  // Basic server-side validation
  if ($name === '' || $email === '' || $message === '') {
    $formFeedback = '<div class="alert alert-danger">Please fill in all fields.</div>';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $formFeedback = '<div class="alert alert-danger">Please enter a valid email address.</div>';
  } else {
    // Execute query here after validations
    try {

      $sql = 'INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)';
      $db->query($sql, [$name, $email, $message]);
      // PRG: redirect after successful insert to avoid duplicate submissions
      header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?sent=1');
      exit;

    } catch (Exception $e) {
      // The wrapper currently handles PDO exceptions internally; still catch any unexpected exceptions
      error_log('Contact insert failed: ' . $e->getMessage());
      $formFeedback = '<div class="alert alert-danger">Failed to send message. Please try again later.</div>';
    }
  }
}

// Show success when redirected after insert
if (isset($_GET['sent']) && $_GET['sent'] == '1') {
  $formFeedback = '<div class="alert alert-success">Thank you — your message has been received.</div>';
}
?>

<body>
  <!-- NAVBAR -->
  <?php require 'partials/navbar.php'; ?>

  <!-- Login/Register Modals -->
  <?php require 'partials/modals.php'; ?>

  <header class="text-center text-white py-5 page-header">
    <div class="container">
      <h1 class="display-4">Contact Us</h1>
    </div>
  </header>

  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card shadow">
            <div class="card-body">
              <h2 class="card-title mb-3">Get in Touch</h2>
              <form id="contactForm" method="post" action="">
                <div class="mb-3">
                  <label for="contactName" class="form-label">Name</label>
                  <input name="name" type="text" class="form-control" id="contactName" placeholder="Your name" required>
                </div>
                <div class="mb-3">
                  <label for="contactEmail" class="form-label">Email</label>
                  <input name="email" type="email" class="form-control" id="contactEmail" placeholder="Your email" required>
                </div>
                <div class="mb-3">
                  <label for="contactMessage" class="form-label">Message</label>
                  <textarea name="message" class="form-control" id="contactMessage" rows="3" placeholder="Type your message"
                    required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
                <div id="formFeedback" class="mt-3"><?= $formFeedback ?></div>
              </form>

              <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title text-light" id="successModalLabel">Your message has been sent!</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- <div class="modal-body text-center">
                      Thank you for your support!
                    </div> -->
                  </div>
                </div>
              </div>
              <hr>
              <h5>Contact Information</h5>
              <ul class="list-unstyled">
                <li><strong>Email:</strong> online@heartspark.com</li>
                <li><strong>Phone:</strong> +1 234 567 8900</li>
                <li><strong>Address:</strong> 123 Sagilid St, Underground City</li>
              </ul>
              <div class="mt-3">
                <iframe class="map-iframe"
                  src="https://www.openstreetmap.org/export/embed.html?bbox=120.9842%2C14.5995%2C120.9842%2C14.5995&amp;layer=mapnik"
                  allowfullscreen="" loading="lazy"></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Client-side contact validation removed so server handles the submission reliably -->
  <script src="scripts/contact.js"></script>

</body>

</html>