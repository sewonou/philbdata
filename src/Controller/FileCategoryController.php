<?php

namespace App\Controller;

use App\Entity\FileCategory;
use App\Form\FileCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileCategoryController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/settings/category/add", name="setting_category_add")
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function add(Request $request):Response
    {
        $category = new FileCategory();
        $form = $this->createForm(FileCategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "La categorie de paramètre <strong>{$category->getTitle()}</strong>  a bien été ajouter");
            $this->manager->persist($category);
            $this->manager->flush();
            return $this->redirectToRoute('setting');
        }


        return $this->render('file_category/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/settings/category/{id}/edit", name="setting_category_edit")
     * @param Request $request
     * @param FileCategory $category
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function edit(Request $request, FileCategory $category):Response
    {
        $form = $this->createForm(FileCategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "La categorie de paramètre <strong>{$category->getTitle()}</strong>  a bien été modifier");
            $this->manager->persist($category);
            $this->manager->flush();
            return $this->redirectToRoute('setting');
        }
        return $this->render('file_category/index.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    /**
     * @Route("/admin/settings/category/{id}/delete", name="setting_category_delete")
     * @param FileCategory $category
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(FileCategory $category):Response
    {
        $this->addFlash('sucess',"La categorie de paramètre <strong>{$category->getTitle()}</strong>  a bien été supprimer");
        $this->manager->remove($category);
        $this->manager->flush();
        return $this->redirectToRoute('setting');
    }
}
