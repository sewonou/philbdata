<?php

namespace App\Controller;

use App\Entity\Syntax;
use App\Form\SyntaxType;
use App\Repository\SyntaxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SyntaxController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, SyntaxRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/syntaxes", name="syntax")
     */
    public function index()
    {
        $syntaxes = $this->repository->findAll();
        return $this->render('syntax/index.html.twig', [
            'syntaxes' => $syntaxes,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/syntaxes/new", name="syntax_add")
     */
    public function create(Request $request):Response
    {
        $syntax = new Syntax();
        $form = $this->createForm(SyntaxType::class, $syntax);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $syntax->setAuthor($user);
            $this->manager->persist($syntax);
            $this->manager->flush();

            $this->addFlash('success', "La syntax <strong>{$syntax->getTitle()}</strong> a bien été ajouter");
            return $this->redirectToRoute('syntax');
        }

        return $this->render('syntax/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Syntax $syntax
     * @return Response
     * @Route("/syntaxes/edit/{id}", name="syntax_edit")
     */
    public function edit(Request $request, Syntax $syntax):Response
    {

        $form = $this->createForm(SyntaxType::class, $syntax);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $syntax->setAuthor($user);
            $this->manager->persist($syntax);
            $this->manager->flush();
            $this->addFlash('success', "La syntax <strong>{$syntax->getTitle()}</strong> a bien été ajouter");
            return $this->redirectToRoute('syntax');
        }

        return $this->render('syntax/form.html.twig', [
            'form' => $form->createView(),
            'syntax' => $syntax,
        ]);
    }

    /**
     * @param Syntax $syntax
     * @return Response
     * @Route("/syntaxes/delete/{id}", name="syntax_delete")
     */
    public function delete(Syntax $syntax):Response
    {
        $this->addFlash('success', "Le syntaxe <strong>{$syntax->getTitle()}</strong> a bien été supprimer");
        $this->manager->remove($syntax);
        $this->manager->flush();
        return $this->redirectToRoute('syntax');
    }
}
