<?php

namespace App\Controller;

use App\Entity\Town;
use App\Form\TownType;
use App\Repository\TownRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TownController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, TownRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/towns", name="town")
     */
    public function index():Response
    {
        return $this->render('town/index.html.twig', [
            'towns'=>$this->repository->findAll(),
        ]);
    }

    /**
     * @Route("/towns/add", name="town_add")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request):Response
    {
        $town = new Town();
        $form = $this->createForm(TownType::class, $town);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "La commune <strong>{$town->getName()}</strong> a bien été ajouter.");
            $this->manager->persist($town);
            $this->manager->flush();
            return  $this->redirectToRoute('town');
        }

        return $this->render('town/add.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/towns/{id}/edit}", name="town_edit")
     * @param Request $request
     * @param Town $town
     * @return Response
     */
    public function edit(Request $request, Town $town):Response
    {
        $form = $this->createForm(TownType::class, $town);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "La commune <strong>{$town->getName()}</strong> a bien été modifier.");
            $this->manager->persist($town);
            $this->manager->flush();
            return  $this->redirectToRoute('town');
        }

        return $this->render('town/edit.html.twig', [
            'form'=>$form->createView(),
            'town'=>$town,
        ]);
    }

    /**
     * @Route("/towns/{id}/delete", name="town_delete")
     * @param Town $town
     * @return Response
     */
    public function delete(Town $town):Response
    {
        $this->addFlash('success', "La commune <strong>{$town->getName()}</strong> a bien été supprimer.");
        $this->manager->remove($town);
        $this->manager->flush();

        return $this->redirectToRoute('town');
    }

    /**
     * @Route("/towns/{id}/show", name="town_show")
     * @param Town $town
     * @return Response
     */
    public function show(Town $town):Response
    {
        return  $this->render('town/show.html.twig', [
            'town'=>$town
        ]);
    }
}
