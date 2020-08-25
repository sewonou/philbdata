<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PointofsaleController extends AbstractController
{
    /**
     * @Route("/pointofsale", name="pointofsale")
     */
    public function index()
    {
        return $this->render('pointofsale/index.html.twig', [
            'controller_name' => 'PointofsaleController',
        ]);
    }
}
