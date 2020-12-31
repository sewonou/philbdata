<?php

namespace App\Controller;

use App\Entity\PriceCategory;
use App\Entity\PriceList;
use App\Form\PriceCategoryType;
use App\Form\PriceListType;
use App\Repository\PriceCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriceListController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/price_list/add", name="price_list_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request):Response
    {
        $price = new PriceList();
        $form = $this->createForm(PriceListType::class, $price);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le prix à bie été ajouter à la catégorie");
            $this->manager->persist($price);
            $this->manager->flush();

            return $this->redirectToRoute('price_category');
        }


        return  $this->render('price_list/add.html.twig', [
            'form'=>$form->createView(),

        ]);
    }

    /**
     * @Route("/price_list/edit/{id}", name="price_list_edit")
     * @param Request $request
     * @param PriceList $price
     * @return Response
     */
    public function edit(Request $request, PriceList $price):Response
    {
        $form = $this->createForm(PriceListType::class, $price);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le prix à bie été ajouter à la catégorie");
            $this->manager->persist($price);
            $this->manager->flush();

            return $this->redirectToRoute('price_category');
        }


        return  $this->render('price_list/add.html.twig', [
            'form'=>$form->createView(),
            'price' => $price,
        ]);
    }

    /**
     * @Route("/price_list/delete/{id}", name="price_list_delete")
     * @param PriceList $price
     * @return Response
     */
    public function delete(PriceList $price):Response
    {
        $this->addFlash('success', "Le prix id: <strong>{$price->getId()}</strong> a bien été supprimer");
        $this->manager->remove($price);
        $this->manager->flush();
        return $this->redirectToRoute('price_category');
    }

    /**
     * @Route("/price_list/{slug}", name="price_list")
     * @param $slug
     * @param PriceCategoryRepository $categoryRepository
     * @return Response
     */
    public function index($slug, PriceCategoryRepository $categoryRepository):Response
    {
        $category = $categoryRepository->findOneBy(['slug'=>$slug]);
        return $this->render('price_list/index.html.twig', [
            'slug' => $slug,
            'category' => $category,
        ]);
    }


}
