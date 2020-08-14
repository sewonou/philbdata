<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ZoneController extends AbstractController
{
    /**
     * @Route("/zone", name="zone")
     */
    public function index()
    {
        return $this->render('zone/index.html.twig', [
            'controller_name' => 'ZoneController',
        ]);
    }
}
