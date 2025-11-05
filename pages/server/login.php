<?php
// href to this for login form submissions
// Determine paths
$configPath = __DIR__ . '/../database/config.php';
if (!file_exists($configPath)) {
    error_log('Register: database config not found at ' . $configPath);
    // Redirect back
    $back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
    header('Location: ../index.php');
    exit;
}

require $configPath;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read POST data
    $email = trim($_POST['email']);
    // password is still hashed at this point
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        // Create database connection
        $db = new Database();

        // Find user by email
        $user = $db->fetchOne('SELECT * FROM users WHERE email = ?', [$email]);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Password is correct - create session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];

            // Get cart count
            $cart_count = $db->fetchOne(
                'SELECT COUNT(*) as count FROM cart_items WHERE user_id = ?',
                [$user['id']]
            );

            // Update session cart count
            $_SESSION['cart_count'] = $cart_count['count'];

            // Redirect to dashboard
            header('Location: ../index.php');
            exit();
        } else {
            $error = 'Invalid email or password';
        }
    }
}
