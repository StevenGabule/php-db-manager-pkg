<?php

namespace Jpgabs\DbManager\Migrations;

use Jpgabs\DbManager\Database\DBConnection;

class MigrationRunner
{
  public function __construct(private DBConnection $dBConnection)
  {
  }

  public function migrate(string $migrationsPath)
  {
    $appliedMigrations = $this->getAppliedMigrations();
    $migrationFiles = glob((string)$migrationsPath . '/*.php');
    sort($migrationFiles);
    foreach ($migrationFiles as $file) {
      $filename = basename($file, '.php');
      if (! in_array($filename, $appliedMigrations)) {
        $migration = require $file;
        if (is_object($migration) && method_exists($migration, 'up')) {
          echo "Running migration: $filename ..." . PHP_EOL;
          $migration->up($this->dBConnection->getPdo());
          $this->recordMigration($filename);
          echo "Done.\n" . PHP_EOL;
        }
      } else {
        echo "Nothing to migrate." . PHP_EOL;
      }
    }
  }

  private function getAppliedMigrations(): array
  {
    $pdo = $this->dBConnection->getPdo();
    $stmt = $pdo->query("SELECT migration_name FROM migrations");

    return $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];
  }

  private function recordMigration(string $migrationName): void
  {
    $pdo = $this->dBConnection->getPdo();
    $stmt = $pdo->prepare("INSERT INTO migrations (migration_name, applied_at) VALUES (:migration_name, :applied_at)");
    $stmt->execute([
      ':migration_name' => $migrationName,
      ':applied_at' => date('Y-m-d H:i:s'),
    ]);
  }
}
