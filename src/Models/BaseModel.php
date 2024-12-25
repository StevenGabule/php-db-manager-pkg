<?php

namespace Jpgabs\DbManager\Models;

use Jpgabs\DbManager\Database\DBConnection;
use PDO;

abstract class BaseModel
{
  protected PDO $pdo;

  public function __construct(DBConnection $dBConnection)
  {
    $this->pdo = $dBConnection->getPdo();
  }

  /**
   * Executes a query with optional parameters.
   * 
   * @param string $sql
   * @param array $params
   * 
   * @return array  Returns the result as an associative array.
   */
  protected function executeQuery(string $sql, array $params = []): array
  {
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
  }

  protected function executeNonQuery(string $sql, array $params = []): bool
  {
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params);
  }
}