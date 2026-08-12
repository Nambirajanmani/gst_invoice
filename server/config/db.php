<?php
/**
 * Database Connection Helper (PDO PostgreSQL)
 * Credentials loaded directly from .env file
 */

function getDbConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    // Load .env file from project root
    $envFile = dirname(__DIR__, 2) . '/.env';
    $env = [];
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            [$key, $value] = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
    }

    $host     = $env['PGHOST']     ?? 'localhost';
    $port     = $env['PGPORT']     ?? '5432';
    $dbname   = $env['PGDATABASE'] ?? '';
    $user     = $env['PGUSER']     ?? '';
    $password = $env['PGPASSWORD'] ?? '';

    if (!$dbname || !$user) {
        throw new \RuntimeException('Database credentials missing. Check your .env file.');
    }

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";

    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
