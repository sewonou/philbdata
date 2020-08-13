<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="homePage")
     */
    public function index()
    {
        return $this->render('dashboard/index.html.twig', [

        ]);
    }
}
