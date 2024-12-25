<?php

return new class {
  public function up(\PDO $pdo)
  {
    // TODO: Write your forward migration (DDL or data changes).
    // For example:
    // $pdo->exec("CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, ...)");
  }
  
  public function down(\PDO $pdo)
  {
    // TODO: Write your rollback migration (optional).
    // For example:
    // $pdo->exec("DROP TABLE users");
  }
};