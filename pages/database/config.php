<?php

// Enhanced session startup and security settings
// (require_once should always be at the top of the file so session is started early)

// Determine whether connection is secure
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$cookieDomain = $_SERVER['HTTP_HOST'] ?? '';

// Use a custom session name to avoid default PHPSESSID collisions
session_name('heartspark_session');

// Enforce strict mode to reject uninitialized session IDs
ini_set('session.use_strict_mode', '1');

// Set secure cookie params (PHP 7.3+ array style). Falls back to traditional call if needed.
if (function_exists('session_set_cookie_params')) {
  // PHP 7.3+ accepts an array for options
  try {
    session_set_cookie_params([
      'lifetime' => 0,          // expire on browser close
      'path' => '/',
      'domain' => $cookieDomain,
      'secure' => $secure,
      'httponly' => true,
      'samesite' => 'Lax'       // consider 'Strict' if compatible with your flows
    ]);
  } catch (ArgumentCountError $e) {
    // Older PHP versions: fall back to positional parameters
    session_set_cookie_params(0, '/', $cookieDomain, $secure, true);
  }
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Prevent session fixation: regenerate ID when session is first created
if (empty($_SESSION['initiated'])) {
  session_regenerate_id(true);
  $_SESSION['initiated'] = time();
}

// Inactivity timeout (seconds). If exceeded, destroy session and start a new one.
$timeout = 1800; // 30 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
  // Clear session array
  $_SESSION = [];
  // Delete session cookie
  if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
      $params['path'], $params['domain'], $params['secure'], $params['httponly']
    );
  }
  session_destroy();
  session_start();
  session_regenerate_id(true);
  $_SESSION['initiated'] = time();
}
$_SESSION['last_activity'] = time();

// Basic session hijack checks: bind session to IP and user agent where possible
$agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
if (!isset($_SESSION['ip']) || !isset($_SESSION['user_agent'])) {
  $_SESSION['ip'] = $ip;
  $_SESSION['user_agent'] = $agent;
} else {
  // If mismatch detected, regenerate session id and update bindings
  if ($_SESSION['user_agent'] !== $agent || ($_SESSION['ip'] !== $ip && !empty($ip))) {
    session_regenerate_id(true);
    $_SESSION['ip'] = $ip;
    $_SESSION['user_agent'] = $agent;
  }
}


class Database {
  private $host = 'localhost';
  private $dbname = 'heartspark';
  private $username = 'root';
  private $password = 'wohnoqjowti0repbIn';
  private $charset = 'utf8mb4';
  private $pdo;


  public function __construct()
  {
    try {
      $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
      $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ];

      $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
    } catch (PDOException $e) {
      die('Database connection failed: ' . $e->getMessage());
    }
  }

  // for connection access
  public function getConnection()
  {
    return $this->pdo;
  }


  // query execution helper
  public function query($sql, $params = [])
  {
    try {
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);
      return $stmt;
    } catch (PDOException $e) {
      die('Query failed: ' . $e->getMessage());
    }
  }


  public function fetchAll($sql, $params = [])
  {
    $stmt = $this->query($sql, $params);
    return $stmt->fetchAll();
  }

  public function fetchOne($sql, $params = [])
  {
    $stmt = $this->query($sql, $params);
    return $stmt->fetch();
  }
}
