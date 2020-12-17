<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriceListController extends AbstractController
{
    /**
     * @Route("/price_list/{slug}", name="price_list")
     * @param $slug
     * @return Response
     */
    public function index($slug):Response
    {

        return $this->render('price_list/index.html.twig', [
            'slug' => $slug,
        ]);
    }


}
