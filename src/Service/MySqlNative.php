<?php

namespace App\Service;

use PDO;

class MySqlNative
{
    /**
     * @var PDO
     */
    private $connection;

    public function __construct(string $dsn)
    {
        $dsn = parse_url($dsn);
        $user = $dsn['user'];
        $pass = $dsn['pass'];
        $path = pathinfo($dsn['path']);
        $host = $dsn['host'] === '127.0.0.1' ? 'localhost' : $dsn['host'];
        $dsn = "{$dsn['scheme']}:host={$host};dbname={$path['basename']}";

        $this->connection = new PDO($dsn, $user, $pass);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function prepare(string $query)
    {
        return $this->connection->prepare($query);
    }
}
