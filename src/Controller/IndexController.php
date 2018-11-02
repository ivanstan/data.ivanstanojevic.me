<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class IndexController extends AbstractController
{
    /** @var string */
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        $catalog = Yaml::parseFile($this->projectDir.'/config/custom/catalog.yaml');

        foreach ($catalog as $key => $item) {
            if (\is_array($item['path'])) {
                $catalog[$key]['path'] = $this->generateUrl($item['path']['route'], $item['path']['params']);
            }
        }

        return $this->render('index.html.twig', ['catalog' => $catalog]);
    }
}
