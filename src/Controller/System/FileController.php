<?php

namespace App\Controller\System;

use App\Entity\File;
use App\Service\System\FileManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/file")
 */
class FileController extends AbstractController
{
    /**
     * @Route("/download/{file}", name="system_file_download")
     * @IsGranted("VIEW", subject="file")
     */
    public function download(File $file, FileManager $manager): BinaryFileResponse
    {
        return new BinaryFileResponse($manager->getAbsolutePath($file));
    }
}
