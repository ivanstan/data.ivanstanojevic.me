<?php

namespace App\Controller\Api;

use App\Converter\TafModelConverter;
use App\Repository\MetarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/taf")
 */
class TafController extends AbstractApiController
{
    /**
     * @Route("/{icao}", name="taf_record")
     */
    public function collection(string $icao, MetarRepository $repository, TafModelConverter $converter): Response
    {
        $taf = $repository->getTaf($icao);

        return $this->response($converter->collection($taf));
    }
}
