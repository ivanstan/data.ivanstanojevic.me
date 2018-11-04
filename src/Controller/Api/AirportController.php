<?php

namespace App\Controller\Api;

use App\Entity\Airport;
use App\Repository\AirportRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/airport")
 */
class AirportController extends AbstractApiController
{
    /**
     * @Route("/{icao}", name="airport_record")
     */
    public function record(string $icao, AirportRepository $repository): Response
    {
        /** @var Airport $record */
        $record = $repository->findOneBy(['icao' => $icao]);

        if ($record === null) {
            throw new NotFoundHttpException(\sprintf('Unable to find record with id %s', $icao));
        }

        return $this->response($record);
    }
}
