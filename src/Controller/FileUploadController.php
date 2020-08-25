<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FileUploadController extends AbstractController
{
    /**
     * @Route("/file/upload", name="file_upload")
     */
    public function index()
    {
        return $this->render('file_upload/index.html.twig', [
            'controller_name' => 'FileUploadController',
        ]);
    }
}
