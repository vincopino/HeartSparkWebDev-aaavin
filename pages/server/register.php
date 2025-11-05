<?php
// Simple registration handler for modal form.

// Determine paths
$configPath = __DIR__ . '/../database/config.php';
if (!file_exists($configPath)) {
    error_log('Register: database config not found at ' . $configPath);
    // Redirect back
    $back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
    // latter part only appends registered=0 to the URL (REMOVE IF NOT NEEDED)
    header('Location: ' . $back . (strpos($back, '?') === false ? '?' : '&') . 'registered=0');
    exit;
}

require $configPath;

// Read POST
$full_name = isset($_POST['fullname']) ? trim((string)$_POST['fullname']) : '';
$address = isset($_POST['address']) ? trim((string)$_POST['address']) : '';
$email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
$contact = isset($_POST['contact']) ? trim((string)$_POST['contact']) : '';
$password = isset($_POST['password']) ? (string)$_POST['password'] : '';
$retype = isset($_POST['confirm_password']) ? (string)$_POST['confirm_password'] : '';

$back = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';

// Basic validation
$error = '';
if ($fullname === '' || $email === '' || $password === '') {
    $error = 'missing_fields';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'invalid_email';
} elseif ($password !== $retype) {
    $error = 'password_mismatch';
}

if ($error !== '') {
    header('Location: ' . $back . (strpos($back, '?') === false ? '?' : '&') . 'registered=0&err=' . urlencode($error));
    exit;
}

$db = new Database();
$pdo = $db->getConnection();

// Check if email already exists
$existing_user = $db->fetchOne('SELECT id FROM users WHERE email = ?', [$email]);

if ($existing_user) {
    header('Location: ' . $back . (strpos($back, '?') === false ? '?' : '&') . 'registered=0&err=email_exists');
    exit;
} else {

    // hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Note: the current schema has "user_address" as the column
        $sql = 'INSERT INTO users (full_name, user_address, email, contact, password_hash) VALUES (?, ?, ?, ?, ?)';
        $db->query($sql, [$full_name, $address, $email, $contact, $hashed_password]);

        header('Location: ' . $back . (strpos($back, '?') === false ? '?' : '&') . 'registered=1');
        exit;
    } catch (Exception $e) {
        error_log('Register insert failed: ' . $e->getMessage());
        header('Location: ' . $back . (strpos($back, '?') === false ? '?' : '&') . 'registered=0&err=server');
        exit;
    }
}

?>