<?php

namespace App\Controller;

use App\Repository\AirportRepository;
use App\Repository\MetarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    /**
     * @Route("/data", name="app_data_canonical")
     * @Route("/", name="app_data_index", host="data.ivanstanojevic.me")
     */
    public function index(MetarRepository $metarRepository, AirportRepository $airportRepository): Response
    {
        $airports = $airportRepository->getCollection($metarRepository->getAirportsWithMetarData());

        return $this->render('pages/data/index.html.twig', [
            'airports' => $airports,
        ]);
    }
}
