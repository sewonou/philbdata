<?php

namespace App\Controller;

use App\Entity\Pointofsale;
use App\Form\PointofsaleType;
use App\Repository\PointofsaleRepository;
use App\Service\SimCardStat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PointofsaleController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, PointofsaleRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/pointofsales", name="pointofsale")
     * @param SimCardStat $simCardStat
     * @return Response
     */
    public function index(SimCardStat $simCardStat):Response
    {
        $pointofsales = $this->repository->findPointofsaleWithTrader(true);
        return $this->render('pointofsale/index.html.twig', [
            'pointofsales' => $pointofsales,
            'simCardStat' => $simCardStat,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/pointofsales/add", name="pointofsale_add")
     */
    public function create(Request $request):Response
    {
        $pointofsale = new Pointofsale();
        $form = $this->createForm(PointofsaleType::class, $pointofsale);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($pointofsale);
            $this->manager->flush();
            $this->addFlash('success', "Le Point de vente <strong>{$pointofsale->getName()}</strong> a bien été ajouter");
            return $this->redirectToRoute('pointofsale');
        }
        return  $this->render('pointofsale/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Pointofsale $pointofsale
     * @return Response
     * @Route("/pointofsales/{id}/edit", name="pointofsale_edit")
     */
    public function edit(Request $request, Pointofsale $pointofsale):Response
    {

        $form = $this->createForm(PointofsaleType::class, $pointofsale);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($pointofsale);
            $this->manager->flush();
            $this->addFlash('success', "Le Point de vente <strong>{$pointofsale->getName()}</strong> a bien été modifier");
            return $this->redirectToRoute('pointofsale');
        }
        return  $this->render('pointofsale/form.html.twig', [
            'form' => $form->createView(),
            'pointofsale' => $pointofsale
        ]);
    }

    /**
     * @param Pointofsale $pointofsale
     * @return Response
     * @Route("/pointofsales/{id}/delete", name="pointofsale_delete")
     */
    public function delete(Pointofsale $pointofsale):Response
    {
        $this->addFlash('success', "Le Point de vente <strong>{$pointofsale->getName()}</strong> a bien été supprimer");
        $this->manager->remove($pointofsale);
        $this->manager->flush();
        return $this->redirectToRoute('pointofsale');
    }

    /**
     * @param Pointofsale $pointofsale
     * @return Response
     * @Route("/pointofsales/{id}/show", name="pointofsale_show")
     */
    public function show(Pointofsale $pointofsale):Response
    {
        return  $this->render('pointofsale/show.html.twig', [
            'pointofsale' => $pointofsale,
        ]);
    }
}
