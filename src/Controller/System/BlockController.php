<?php

namespace App\Controller\System;

use App\Entity\Lock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlockController extends AbstractController
{
    /**
     * @Route("/admin/block", name="block_index")
     */
    public function index(): Response
    {
        return $this->render('pages/block/index.html.twig', [
            'active' => $this->getDoctrine()->getRepository(Lock::class)->getActiveLocks(),
            'expired' => $this->getDoctrine()->getRepository(Lock::class)->getExpiredLocks()
        ]);
    }

    /**
     * @Route("/admin/block/{id}/delete", name="block_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Lock $lock): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lock->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lock);
            $entityManager->flush();
        }

        return $this->redirectToRoute('block_index');
    }
}
