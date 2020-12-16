<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SyntaxController extends AbstractController
{
    /**
     * @Route("/syntax", name="syntax")
     */
    public function index()
    {
        return $this->render('syntax/index.html.twig', [
            'controller_name' => 'SyntaxController',
        ]);
    }
}
