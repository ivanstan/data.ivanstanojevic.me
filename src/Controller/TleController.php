<?php

namespace App\Controller;

use App\Converter\TleModelConverter;
use App\Entity\Tle;
use App\Repository\TleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TleController extends AbstractController
{
    /**
     * @Route(path="/tle/view", name="app_tle_view")
     * @Route(path="/tle/view/{id}", name="app_tle_view_item")
     */
    public function view(?string $id, TleModelConverter $converter, TleRepository $repository): Response
    {
        $model = null;
        if ($id !== null) {
            /** @var Tle $tle */
            $tle = $repository->findOneBy(['satelliteId' => $id]);

            if ($tle === null) {
                throw new NotFoundHttpException(\sprintf('Unable to find TLE record with id %s', $id));
            }

            $model = $converter->convert($tle);
        }

        return $this->render('pages/tle/view.html.twig', ['model' => $model]);
    }
}
