<?php

namespace App\Tests\Acceptance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SanityTest extends WebTestCase
{
    protected static $pages = [
        '/',
        '/data',
        '/blog',
        '/rs/blog',
        '/api/tle/docs',
        '/api/tle/43550',
        '/api/tle',
        '/firms',
        '/airport/lybe',
    ];

    public function __construct()
    {
        parent::__construct();
        self::createClient();
    }

    public function testPages(): void
    {
        foreach (self::$pages as $page) {
            $this->visit($page);
        }
    }

    protected function visit(string $path): Response
    {
        self::$client->request('GET', $path);
        static::assertEquals(
            200,
            self::$client->getResponse()->getStatusCode(),
            \sprintf('Endpoint %s returned HTTP code different to 200', $path)
        );

        return self::$client->getResponse();
    }
}
