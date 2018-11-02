<?php

namespace App\Controller\Api;

use App\Repository\FirmsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirmsController extends AbstractApiController
{
    /**
     * @Route("/api/firms", name="firms_collection")
     * @throws \Exception
     */
    public function collection(Request $request, FirmsRepository $repository): Response
    {
        $from = $request->get(self::DATE_FROM_PARAM, 'now');
        $to = $request->get(self::DATE_TO_PARAM);
        $inverval = $request->get(self::DATE_INTERVAL_PARAM);
        $timezone = new \DateTimeZone('UTC');
        $interval = \DateInterval::createFromDateString('-1 day');

        if ($to !== null && $inverval !== null) {
            throw new \RuntimeException(
                \sprintf(
                    'Parameters %s and %s can\'t be used in conjunction.',
                    self::DATE_TO_PARAM,
                    self::DATE_INTERVAL_PARAM
                )
            );
        }

        /** @var \DateTime $to */
        $from = new \DateTime($from);
        $to = clone $from;
        $to->sub(new \DateInterval('P1D'));

        $collection = $repository->collection($from, $to);

        return $this->response(
            $collection
        );
    }
}
