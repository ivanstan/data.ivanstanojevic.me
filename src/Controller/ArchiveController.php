<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/archive")
 */
class ArchiveController extends AbstractController
{
    /**
     * @Route("/video", name="video_gallery")
     */
    public function index()
    {
        return $this->render('pages/archive/video-gallery.html.twig');
    }
}
