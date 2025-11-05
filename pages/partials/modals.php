<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="loginModalLabel">Login</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="server/login.php">
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <!-- Create Account -->
        <div class="text-center mt-3">
          <a href="#" id="loginTextLink" class="text-info">Create an Account?</a>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="registerModalLabel">Create Account</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <?php
        // Show feedback if redirected from server/register.php
        $registerFeedback = '';
        if (isset($_GET['registered'])) {
          if ($_GET['registered'] == '1') {
            $registerFeedback = '<div class="alert alert-success">Account created successfully.</div>';
          } else {
            $err = isset($_GET['err']) ? $_GET['err'] : '';
            if ($err === 'password_mismatch') {
              $registerFeedback = '<div class="alert alert-danger">Passwords do not match.</div>';
            } elseif ($err === 'invalid_email') {
              $registerFeedback = '<div class="alert alert-danger">Invalid email address.</div>';
            } elseif ($err === 'missing_fields') {
              $registerFeedback = '<div class="alert alert-danger">Please fill in all required fields.</div>';
            } else {
              $registerFeedback = '<div class="alert alert-danger">Failed to create account. Please try again later.</div>';
            }
          }
        }
        echo $registerFeedback;
        ?>

        <form method="POST" action="server/register.php">
          <div class="mb-3">
            <label for="fullname" class="form-label">Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname"
              value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>" required>
          </div>

          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address"
              value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email"
              value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
          </div>

          <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" class="form-control" id="contact" name="contact"
              value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <small class="text-muted">Must be at least 6 characters</small>
          </div>

          <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        </form>
      </div>
    </div>
  </div>
</div>