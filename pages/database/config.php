<?php

// Starts session here (require_once should always be at the top of the file)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


class Database {
  private $host = 'localhost';
  private $dbname = 'heart_spark';
  private $username = 'root';
  private $password = 'password';
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
