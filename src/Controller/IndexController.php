<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    public const API = [
        [
            'name' => 'TLE (Two line element set) API',
            'description' => 'Orbital perturbation data sets periodically refined so as to maintain a reasonable prediction capability on all space objects.',
            'path' => [
                'route' => 'app_api_docs',
                'params' => ['name' => 'tle']
            ],
        ],
        [
            'name' => 'METAR API',
            'description' => 'Meteorological Terminal Air Report METAR - Aviation weather report updated every 15 minutes currently available for LYBE airport.',
            'path' => [
                'route' => 'app_api_docs',
                'params' => ['name' => 'metar']
            ],
        ],
    ];

    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        $apis = [];
        foreach (self::API as $key => $api) {
            $apis[$key] = $api;
            $apis[$key]['path'] = $this->generateUrl($api['path']['route'], $api['path']['params']);
        }

        return $this->render('index.html.twig', ['apis' => $apis]);
    }
}
