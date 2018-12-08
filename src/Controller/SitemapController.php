<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route(path="/sitemap.xml", name="sitemap")
     */
    public function xmlsitemap(): Response
    {
        return $this->render('pages/sitemap/index.html.twig', ['urls' => []]);
    }
}
