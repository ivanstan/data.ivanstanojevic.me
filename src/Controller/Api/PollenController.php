<?php

namespace App\Controller\Api;

use App\Repository\PollenRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/pollen")
 */
class PollenController extends AbstractApiController
{
    /**
     * @Route("/", name="pollen_chart")
     */
    public function chart(PollenRepository $repository): Response
    {
        return $this->response($repository->getAggregated());
    }
}
