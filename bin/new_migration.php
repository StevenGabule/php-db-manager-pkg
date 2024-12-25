#!/usr/bin/env php
<?php

/**
 * A simple script to generate a new migration file in the `migrations` folder.
 *
 * Usage (from project root):
 *    php bin/new_migration.php create_users_table
 *
 * This will create a file like:
 *    migrations/20241225000000_create_users_table.php
 */

 $description = $argv[1] ?? null;
if (! $description) {
  echo "Usage: php new_migration.php [migration_description]\n";
  exit(1);
}

$timestamp = date('YmdHis');
$filename = "{$timestamp}_{$description}.php";

$content = <<<PHP
<?php

return new class {
  public function up(\PDO \$pdo)
  {
    // TODO: Write your forward migration (DDL or data changes).
    // For example:
    // \$pdo->exec("CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, ...)");
  }
  
  public function down(\PDO \$pdo)
  {
    // TODO: Write your rollback migration (optional).
    // For example:
    // \$pdo->exec("DROP TABLE users");
  }
};
PHP;

$migrationsPath = __DIR__ . '/../migrations';
file_put_contents("$migrationsPath/$filename", $content);
echo "Create migration: $filename" . PHP_EOL;
