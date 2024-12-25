<?php

namespace Jpgabs\DbManager\Models;

use Jpgabs\DbManager\Database\DBConnection;

class UserModel extends BaseModel
{
  protected string $tableName = 'users';

  public function __construct(DBConnection $dBConnection)
  {
    parent::__construct($dBConnection);
  }

  /**
   * Fetch all users.
   * @return array
   */
  public function getAllUsers(): array
  {
    $sql = "SELECT * FROM {$this->tableName}";

    return $this->executeQuery($sql);
  }

  /**
   * Fetch a user by ID.
   *
   *
   * @param int $id
   * @return array|null
   */
  public function getUserById(int $id): ?array
  {
    $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";
    $result = $this->executeQuery($sql, ['id' => $id]);

    return $result[0] ?? null;
  }

  /**
   * Insert a new user
   * @param array $data
   * @return bool
   */
  public function insertUser(array $data): bool
  {
    $sql = "INSERT INTO {$this->tableName} (name, email, created_at) VALUES(:name, :email, :created_at)";

    return $this->executeNonQuery($sql, [
      'name' => $data['name'] ?? 'guest',
      'email' => $data['email'] ?? '',
      'created_at' => date('Y-m-d H:i:s'),
    ]);
  }

  /**
   * Update a user
   *
   * @param int $id
   * @param array $data
   * @return bool
   */
  public function updateUser(int $id, array $data): bool
  {
    $sql = "UPDATE {$this->tableName} SET name = :name, email=:email where id = :id";

    return $this->executeNonQuery($sql, [
      'id' => $id,
      'name' => $data['name'],
      'email' => $data['email'],
    ]);
  }

  /**
   * Delete a user
   *
   * @param int $id
   * @return bool
   */
  public function deleteUser(int $id): bool
  {
    $sql = "DELETE FROM {$this->tableName} WHERE id = :id";

    return $this->executeNonQuery($sql, ['id' => $id]);
  }
}
