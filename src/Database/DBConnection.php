<?php

namespace Jpgabs\DbManager\Database;

use PDO;
use PDOException;

class DBConnection
{
  private PDO $pdo;

  /**
   * Constructor initializes the PDO connection.
   *
   * @param string $dsn       The data Source Name (e.g mysql:host=localhost;dbname=test_db)
   * @param string $username  The database username
   * @param string $password  The database password
   * @param array $options    Additional PDO options
   *
   * @throws PDOException
   */
  public function __construct(private string $dsn, private string $username, private string $password, private array $options = [])
  {
    $this->connect();
  }

  /**
   * Establishes the PDO connection.
   * @throws PDOException
   * @return void
   */
  private function connect(): void
  {
    try {
      $this->pdo = new PDO($this->dsn, $this->username, $this->password, $this->options);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      // In production, you'd likely log this rather than echo.
      echo "Connection failed: {$e->getMessage()}";

      throw $e;
    }
  }

  /**
   * Return the active PDO instance
   * @return \PDO
   */
  public function getPdo(): PDO
  {
    return $this->pdo;
  }

  public function beginTransaction(): bool
  {
    return $this->pdo->beginTransaction();
  }

  public function commit(): bool
  {
    return $this->pdo->commit();
  }

  public function rollback(): bool
  {
    return $this->pdo->rollBack();
  }
}
