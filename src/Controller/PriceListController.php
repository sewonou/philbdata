<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PriceListController extends AbstractController
{
    /**
     * @Route("/price/list", name="price_list")
     */
    public function index()
    {
        return $this->render('price_list/index.html.twig', [
            'controller_name' => 'PriceListController',
        ]);
    }
}
