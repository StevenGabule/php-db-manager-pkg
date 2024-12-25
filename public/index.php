<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;
use Jpgabs\DbManager\Database\DBConnection;
use Jpgabs\DbManager\Models\UserModel;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dbConnection = new DBConnection(
  dsn: sprintf('mysql:host=:%s;port=:%s;dbname=:%s', $_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_NAME'], ),
  username: $_ENV['DB_USER'],
  password: $_ENV['DB_PASS'],
  options: []
);

$userModel = new UserModel($dbConnection);
$users = $userModel->getAllUsers();
print_r($users);

echo PHP_EOL;

$inserted = $userModel->insertUser([
  'name' => 'test name',
  'email' => 'testing@gmail.com',
]);
echo "INSERT SUCCESSFUL?" . ($inserted ? "Yes" : "No") . PHP_EOL;

$updated = $userModel->updateUser(5, [
  'name' => 'testing name updated',
  'email' => 'emailupdated@gmail.com',
]);
echo "UPDATE SUCCESSFUL?" . ($updated ? "Yes" : "No") . PHP_EOL;

$deleted = $userModel->deleteUser(5);
echo "DELETE SUCCESSFUL?" . ($deleted ? "Yes" : "No") . PHP_EOL;
