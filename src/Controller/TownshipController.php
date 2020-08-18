<?php

namespace App\Controller;

use App\Entity\Township;
use App\Form\TownshipType;
use App\Repository\TownshipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TownshipController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, TownshipRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/townships", name="township")
     */
    public function index():Response
    {
        return $this->render('township/index.html.twig', [
            'townships'=>$this->repository->findAll(),
        ]);
    }

    /**
     * @Route("/townships/add", name="township_add")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request):Response
    {
        $township = new Township();
        $form = $this->createForm(TownshipType::class, $township);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le canton <strong>{$township->getName()}</strong> a bien été ajouter.");
            $this->manager->persist($township);
            $this->manager->flush();
            return  $this->redirectToRoute('township');
        }

        return $this->render('township/add.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/townships/{id}/edit}", name="township_edit")
     * @param Request $request
     * @param Township $township
     * @return Response
     */
    public function edit(Request $request, Township $township):Response
    {
        $form = $this->createForm(TownshipType::class, $township);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le canton <strong>{$township->getName()}</strong> a bien été modifier.");
            $this->manager->persist($township);
            $this->manager->flush();
            return  $this->redirectToRoute('township');
        }

        return $this->render('township/edit.html.twig', [
            'form'=>$form->createView(),
            'township'=>$township,
        ]);
    }

    /**
     * @Route("/townships/{id}/delete", name="township_delete")
     * @param Township $township
     * @return Response
     */
    public function delete(Township $township):Response
    {
        $this->addFlash('success', "Le canton <strong>{$township->getName()}</strong> a bien été supprimer.");
        $this->manager->remove($township);
        $this->manager->flush();

        return $this->redirectToRoute('township');
    }

    /**
     * @Route("/townships/{id}/show", name="township_show")
     * @param Township $township
     * @return Response
     */
    public function show(Township $township):Response
    {
        return  $this->render('township/show.html.twig', [
            'township'=>$township
        ]);
    }
}
