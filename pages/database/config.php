<?php

class SessionManager
{
    private static $initialized = false;
    private static $opts = [
        'timeout' => 1800,            
        'samesite' => 'Lax',          
        'force_secure' => false,      
        'bind_ip' => false,           
        'name' => 'heartspark_session'
    ];

    public static function initialize(array $options = [])
    {
        if (self::$initialized) return;
        self::$opts = array_merge(self::$opts, $options);

        $secure = self::$opts['force_secure'] || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        $cookieDomain = $_SERVER['HTTP_HOST'] ?? '';

        session_name(self::$opts['name']);
        ini_set('session.use_strict_mode', '1');

        
        if (function_exists('session_set_cookie_params')) {
            try {
                session_set_cookie_params([
                    'lifetime' => 0,
                    'path' => '/',
                    'domain' => $cookieDomain,
                    'secure' => $secure,
                    'httponly' => true,
                    'samesite' => self::$opts['samesite']
                ]);
            } catch (\Throwable $e) {
                
                session_set_cookie_params(0, '/', $cookieDomain, $secure, true);
            }
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        
        if (empty($_SESSION['initiated'])) {
            session_regenerate_id(true);
            $_SESSION['initiated'] = time();
        }

        
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > self::$opts['timeout']) {
            self::destroy();
            session_start();
            session_regenerate_id(true);
            $_SESSION['initiated'] = time();
        }
        $_SESSION['last_activity'] = time();

        
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        if (!isset($_SESSION['user_agent'])) {
            $_SESSION['user_agent'] = $agent;
        } elseif ($_SESSION['user_agent'] !== $agent) {
            session_regenerate_id(true);
            $_SESSION['user_agent'] = $agent;
        }

        
        if (self::$opts['bind_ip']) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            if (!isset($_SESSION['ip'])) {
                $_SESSION['ip'] = $ip;
            } elseif (!empty($ip) && $_SESSION['ip'] !== $ip) {
                session_regenerate_id(true);
                $_SESSION['ip'] = $ip;
            }
        }

        self::$initialized = true;
    }

    public static function destroy()
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        self::$initialized = false;
    }

    public static function requireLogin($redirect = '/pages/login.php')
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    public static function regenerate()
    {
        session_regenerate_id(true);
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
}


SessionManager::initialize();

function ss_destroy_session()
{
    SessionManager::destroy();
}

function ss_require_login()
{
    SessionManager::requireLogin();
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

  
  public function getConnection()
  {
    return $this->pdo;
  }


  
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

