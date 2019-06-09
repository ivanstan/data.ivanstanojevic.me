<?php

namespace App\Controller;

use App\Converter\MetarModelConverter;
use App\Entity\Airport;
use App\Entity\Metar;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AirportController extends AbstractController
{
    /**
     * @Route("/airport/{icao}", name="airport_show")
     */
    public function view(string $icao, MetarModelConverter $converter): Response
    {
        $airport = $this->getDoctrine()->getRepository(Airport::class)->findOneBy(['icao' => $icao]);

        try {
            $metar = $this->getDoctrine()->getRepository(Metar::class)->latest($icao);
        } catch (NonUniqueResultException $exception) {
            $metar = null;
        }

        return $this->render('pages/airport/view.html.twig', [
            'icao' => $icao,
            'airport' => $airport,
            'metar' => $converter->convert($metar),
        ]);
    }
}
