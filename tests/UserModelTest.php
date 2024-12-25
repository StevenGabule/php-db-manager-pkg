<?php

namespace Tests;

use Jpgabs\DbManager\Database\DBConnection;
use Jpgabs\DbManager\Models\UserModel;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
  private static ?DBConnection $dbConnection = null;
  private static string $tableSql = <<<SQL
    CREATE TABLE IF NOT EXISTS users (
      id         int auto_increment primary key,
      name       varchar(255)                         null,
      email      varchar(255)                         null,
      password   varchar(255)                         null,
      created_at datetime default current_timestamp() null,
      updated_at datetime default current_timestamp() null on update current_timestamp()
    ) collate = utf8mb4_unicode_ci;
  SQL;

  public static function setUpBeforeClass(): void
  {
    self::$dbConnection = new DBConnection(
      dsn: "mysql:host=172.19.80.1;port=3307;dbname=blogging_db_test",
      username: "jpgabs",
      password: "password",
    );
    self::$dbConnection->getPdo()->exec(self::$tableSql);
  }

  protected function setUp(): void
  {
    self::$dbConnection->getPdo()->exec("TRUNCATE TABLE users");
  }

  public function testInsertUser(): void
  {
    $userModel = new UserModel(self::$dbConnection);
    $result = $userModel->insertUser([
      'name' => 'john',
      'email' => 'john@gmail.com',
    ]);

    $this->assertTrue($result);

    $allUsers = $userModel->getAllUsers();
    $this->assertCount(1, $allUsers, 'There should be exactly 1 user in the database.');

    // check the fields of the inserted user
    $insertedUser = $allUsers[0];
    $this->assertEquals('john', $insertedUser['name']);
    $this->assertEquals('john@gmail.com', $insertedUser['email']);
  }

  public function testGetUserById(): void
  {
    $userModel = new UserModel(self::$dbConnection);
    $userModel->insertUser([
      'name' => 'fetch_me',
      'email' => 'fetch@example.com',
    ]);

    // get last inserted id via pdo
    $lastId = self::$dbConnection->getPdo()->lastInsertId();

    // fetch the user by ID
    $fetchedUser = $userModel->getUserById((int)$lastId);

    // ensure we got a user
    $this->assertNotNull($fetchedUser, 'The fetched user should not be null.');
    $this->assertEquals($lastId, $fetchedUser['id']);
    $this->assertEquals('fetch_me', $fetchedUser['name']);
    $this->assertEquals('fetch@example.com', $fetchedUser['email']);
  }

  public function testGetAllUsers(): void
  {
    $userModel = new UserModel(self::$dbConnection);

    $userModel->insertUser(['name' => 'first_user', 'email' => 'first@example.com']);
    $userModel->insertUser(['name' => 'second_user', 'email' => 'second@example.com']);
    $userModel->insertUser(['name' => 'third_user', 'email' => 'third@example.com']);

    $allUsers = $userModel->getAllUsers();
    $this->assertCount(3, $allUsers, 'There should be exactly 3 users in the database.');

    $names = array_column($allUsers, 'name');
    $this->assertContains('first_user', $names);
    $this->assertContains('second_user', $names);
    $this->assertContains('third_user', $names);
  }

  public function testUpdateUser(): void
  {
    $userModel = new UserModel(self::$dbConnection);
    $userModel->insertUser([
      'name' => 'john',
      'email' => 'joh@gmail.com',
    ]);

    $lastId = self::$dbConnection->getPdo()->lastInsertId();
    $newData = [
      'name' => 'john update',
      'email' => 'joh_update@gmail.com',
    ];
    $updated = $userModel->updateUser((int)$lastId, $newData);
    $this->assertTrue($updated, 'Update should return true');

    $updatedUser = $userModel->getUserById((int)$lastId);
    $this->assertNotNull($updatedUser, 'Updated user should not be null.');
    $this->assertEquals('john update', $updatedUser['name']);
    $this->assertEquals('joh_update@gmail.com', $updatedUser['email']);
  }

  public function testDeleteUser(): void
  {
    $userModel = new UserModel(self::$dbConnection);
    $userModel->insertUser([
      'name' => 'name',
      'email' => 'emailme@gmail.com',
    ]);

    $lastId = self::$dbConnection->getPdo()->lastInsertId();

    $deleted = $userModel->deleteUser((int)$lastId);
    $this->assertTrue($deleted, 'Delete should return true.');

    $user = $userModel->getUserById((int)$lastId);
    $this->assertNull($user, 'The user should no longer exists after deletion.');

  }
}
