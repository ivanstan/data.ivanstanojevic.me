<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/archive")
 */
class ArchiveController extends AbstractController
{
    /**
     * @Route("/video", name="archive_video")
     */
    public function video(): Response
    {
        return $this->render('pages/archive/video.html.twig');
    }

    /**
     * @Route("/audio", name="archive_audio")
     */
    public function audio(): Response
    {
        return $this->render('pages/archive/audio.html.twig');
    }

    /**
     * @Route("/documents", name="archive_document")
     */
    public function document(): Response
    {
        return $this->render('pages/archive/document.html.twig');
    }
}
