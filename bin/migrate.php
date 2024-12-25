#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Jpgabs\DbManager\Database\DBConnection;
use Jpgabs\DbManager\Migrations\MigrationRunner;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dbConnection = new DBConnection(dsn: "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}", username: $_ENV['DB_USER'], password: $_ENV['DB_PASS']);
$runner = new MigrationRunner($dbConnection);
$runner->migrate(__DIR__ . '/../migrations');
