<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TownshipController extends AbstractController
{
    /**
     * @Route("/township", name="township")
     */
    public function index()
    {
        return $this->render('township/index.html.twig', [
            'controller_name' => 'TownshipController',
        ]);
    }
}
