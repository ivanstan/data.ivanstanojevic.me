<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PingTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = static::createClient();
    }

    public function testPages(): void
    {
        $this->visit('/');
        $this->visit('/data');
        $this->visit('/blog');
        $this->visit('/rs/blog');

        $this->visit('/api/tle/docs');
        $this->visit('/api/tle/43550');
        $this->visit('/api/tle');

        $this->visit('/firms');
        $this->visit('/airport/lybe');
    }

    private function visit(string $path): void
    {
        $this->client->request('GET', $path);
        static::assertEquals(
            200,
            $this->client->getResponse()->getStatusCode(),
            \sprintf('Endpoint %s returned HTTP code different to 200', $path)
        );
    }
}
