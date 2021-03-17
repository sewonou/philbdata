<?php

namespace App\Controller;

use App\Entity\PriceCategory;
use App\Entity\PriceList;
use App\Form\PriceCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriceListController extends AbstractController
{
    private $manager;
    private $repository;

    /**
     * @Route("/price_list/add", name="price_list_add")
     * @return Response
     */
    public function add():Response
    {
        $price = new PriceCategory();
        $form = $this->createForm(PriceCategoryType::class, $price);


        return  $this->render('price_list/add.html.twig', [
            'form'=>$form->createView(),

        ]);
    }

    public function edit():Response
    {

    }

    public function delete():Response
    {

    }

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
