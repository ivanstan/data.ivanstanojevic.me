<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
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

        $this->visit('/api/tle/docs');
        $this->visit('/api/tle');
        $this->visit('/api/tle/43630');
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
