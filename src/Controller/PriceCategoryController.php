<?php

namespace App\Controller;

use App\Entity\PriceCategory;
use App\Form\PriceCategoryType;
use App\Repository\PriceCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PriceCategoryController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, PriceCategoryRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/price/category", name="price_category")
     */
    public function index():Response
    {
        $priceCategories = $this->repository->findAll();
        return $this->render('price_category/index.html.twig', [
            'priceCategories' => $priceCategories,
        ]);
    }

    /**
     * @Route("/price/category/add", name="price_category_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request):Response
    {
        $category = new PriceCategory();
        $form = $this->createForm(PriceCategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "La syntax <strong>{$category->getTitle()}</strong> a bien été ajouter");
            $this->manager->persist($category);
            $this->manager->flush();
            return $this->redirectToRoute('price_category');
        }
        return $this->render('price_category/form.html.twig', [
            'form'=>$form->createView(),

        ]);
    }

    /**
     * @Route("/price/category/edit/{id}", name="price_category_edit")
     * @param Request $request
     * @param PriceCategory $category
     * @return Response
     */
    public function edit(Request $request, PriceCategory $category):Response
    {
        $form = $this->createForm(PriceCategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "La syntax <strong>{$category->getTitle()}</strong> a bien été ajouter");
            $this->manager->persist($category);
            $this->manager->flush();
            return $this->redirectToRoute('price_category');
        }
        return $this->render('price_category/form.html.twig', [
            'form'=>$form->createView(),
            'category'=> $category,
        ]);
    }

    /**
     * @Route("/price/category/delete/{id}", name="price_category_delete")
     * @param PriceCategory $category
     * @return Response
     */
    public function delete(PriceCategory $category):Response
    {
        $this->addFlash('success', "Le categorie <strong>{$category->getTitle()}</strong> a bien été supprimer");
        $this->manager->remove($category);
        $this->manager->flush();
        return $this->redirectToRoute('price_category');
    }
}
