<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DistrictController extends AbstractController
{
    /**
     * @Route("/district", name="district")
     */
    public function index()
    {
        return $this->render('district/index.html.twig', [
            'controller_name' => 'DistrictController',
        ]);
    }
}
