<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PrefectureController extends AbstractController
{
    /**
     * @Route("/prefecture", name="prefecture")
     */
    public function index()
    {
        return $this->render('prefecture/index.html.twig', [
            'controller_name' => 'PrefectureController',
        ]);
    }
}
