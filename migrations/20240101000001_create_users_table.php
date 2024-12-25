<?php

return new class () {
  public function up(\PDO $pdo)
  {
    $sql = <<<SQL
        CREATE TABLE IF NOT EXISTS TBLUsers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB;
    SQL;
    $pdo->exec($sql);
  }
  public function down(\PDO $pdo)
  {
    $pdo->exec("DROP TABLE IF EXISTS TBLUsers");
  }
};
