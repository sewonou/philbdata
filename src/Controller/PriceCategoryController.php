<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PriceCategoryController extends AbstractController
{
    /**
     * @Route("/price/category", name="price_category")
     */
    public function index()
    {
        return $this->render('price_category/index.html.twig', [
            'controller_name' => 'PriceCategoryController',
        ]);
    }
}
