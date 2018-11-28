<?php

namespace App\Service;

class Ip2Country
{
    /** @var MySqlNative */
    private $native;

    public function __construct(MySqlNative $native)
    {
        $this->native = $native;
    }

    public function find(string $ip): ?string
    {
        $query = '
            SELECT country FROM ip_country WHERE         
            (INET_ATON(:ip) BETWEEN INET_ATON(start) AND INET_ATON(end));
        ';
        $query = $this->native->prepare($query);
        $query->bindParam('ip', $ip);
        $query->execute();

        $data = $query->fetch(\PDO::FETCH_ASSOC);

        return $data['country'] ?? null;
    }
}
