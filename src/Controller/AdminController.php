<?php

namespace App\Controller;

use App\Entity\Watchdog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/watchdog", name="admin_watchdog")
     */
    public function index(): Response
    {
        return $this->render('pages/admin/watchdog.html.twig', [
            'log' => $this->getDoctrine()->getRepository(Watchdog::class)->findAll()
        ]);
    }
}
