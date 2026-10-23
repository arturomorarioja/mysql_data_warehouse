<?php
declare(strict_types=1);

final class Database
{
    public static function connect(
        string $host,
        int $port,
        ?string $database,
        string $user,
        string $password
    ): PDO {
        $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";

        if ($database !== null) {
            $dsn .= ";dbname={$database}";
        }

        return new PDO(
            $dsn,
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
}
