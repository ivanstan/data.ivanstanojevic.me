<?php

namespace App\Tests\Acceptance;

use App\DataFixtures\TleFixtures;
use App\Tests\AbstractWebTestCase;

class TleTest extends AbstractWebTestCase
{
    public function testDocumentationIsAvailable(): void
    {
        $response = $this->get('/api/tle/docs');

        self::assertEquals(200, $response->getStatusCode(), 'Assert documentation is available');
    }

    public function testTleSingleRecord(): void
    {
        $tle = TleFixtures::create();

        $response = $this->get('/api/tle/'.$tle->getSatelliteId());

        self::assertEquals(200, $response->getStatusCode());

        $response = $this->toArray($response);

        self::assertArrayHasKey('@id', $response);
        self::assertArrayHasKey('@type', $response);
        self::assertArrayHasKey('satelliteId', $response);
        self::assertArrayHasKey('name', $response);
        self::assertArrayHasKey('date', $response);
        self::assertArrayHasKey('line1', $response);
        self::assertArrayHasKey('line2', $response);

        self::assertEquals('/api/tle/'.$tle->getSatelliteId(), $response['@id']);
        self::assertEquals('TleModel', $response['@type']);
        self::assertEquals($tle->getSatelliteId(), $response['satelliteId']);
        self::assertEquals($tle->getName(), $response['name']);
        self::assertEquals(TleFixtures::$date, $response['date']);
        self::assertEquals($tle->getLine1(), $response['line1']);
        self::assertEquals($tle->getLine2(), $response['line2']);
    }

    public function testTleCollectionRecord(): void
    {
        $pageSize = 2;

        $response = $this->get(
            '/api/tle',
            [
                'page-size' => $pageSize,
            ]
        );

        self::assertEquals(200, $response->getStatusCode());

        $response = $this->toArray($response);

        self::assertArrayHasKey('@context', $response);
        self::assertEquals($response['@context'], 'http://www.w3.org/ns/hydra/context.jsonld');

        self::assertArrayHasKey('@id', $response);
        self::assertEquals($response['@id'], '/api/tle');

        self::assertArrayHasKey('@type', $response);
        self::assertEquals($response['@type'], 'Collection');

        self::assertArrayHasKey('totalItems', $response);
        self::assertEquals($response['totalItems'], 10);

        self::assertArrayHasKey('member', $response);
        self::assertEquals(count($response['member']), $pageSize);

        self::assertArrayHasKey('parameters', $response);
        $parameters = $response['parameters'];

        self::assertArrayHasKey('search', $parameters);
        self::assertEquals($parameters['search'], '*');

        self::assertArrayHasKey('sort', $parameters);
        self::assertEquals($parameters['sort'], 'name');

        self::assertArrayHasKey('sort-dir', $parameters);
        self::assertEquals($parameters['sort-dir'], 'asc');

        self::assertArrayHasKey('page', $parameters);
        self::assertEquals($parameters['page'], 1);

        self::assertArrayHasKey('page-size', $parameters);
        self::assertEquals($parameters['page-size'], $pageSize);

        self::assertArrayHasKey('view', $response);
        $view = $response['view'];

        self::assertArrayHasKey('@id', $view);
        self::assertEquals($view['@id'], '/api/tle?page-size=2&page=1');

        self::assertArrayHasKey('@type', $view);
        self::assertEquals($view['@type'], 'PartialCollectionView');

        self::assertArrayHasKey('first', $view);
        self::assertEquals($view['first'], '/api/tle?page-size=2&page=1');

        self::assertArrayHasKey('next', $view);
        self::assertEquals($view['next'], '/api/tle?page-size=2&page=2');

        self::assertArrayHasKey('last', $view);
        self::assertEquals($view['last'], '/api/tle?page-size=2&page=5');
    }

    public function testDocumentationIsCorrect(): void
    {
        $response = $this->get('/api/tle/json');

        self::assertEquals(200, $response->getStatusCode(), 'Assert json documentation is available');

        $response = $this->toArray($response);
    }
}
