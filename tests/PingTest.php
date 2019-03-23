<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PingTest extends WebTestCase
{
    protected static $pages = [
        '/',
        '/login',
    ];

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client;

    public function __construct()
    {
        $this->client = static::createClient();
    }

    public function testPages(): void
    {
        foreach (self::$pages as $page) {
            $this->visit($page);
        }
    }

    protected function visit(string $path): Response
    {
        $this->client->request('GET', $path);
        static::assertEquals(
            200,
            $this->client->getResponse()->getStatusCode(),
            \sprintf('Endpoint %s returned HTTP code different to 200', $path)
        );

        return $this->client->getResponse();
    }
}
