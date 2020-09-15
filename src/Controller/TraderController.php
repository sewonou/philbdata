<?php

namespace App\Controller;

use App\Entity\Trader;
use App\Form\TraderType;
use App\Repository\TraderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TraderController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, TraderRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/traders", name="trader")
     */
    public function index():Response
    {
        $traders =$this->repository->findBy(['isTrader'=>true, 'isActive'=>true],['fullName'=>'ASC'],null, null);
        return $this->render('trader/index.html.twig', [
            'traders' => $traders,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/traders/add", name="trader_add")
     */
    public function add(Request $request):Response
    {
        $trader = new  Trader();
        $user = $this->getUser();
        $form = $this->createForm(TraderType::class, $trader);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le commercial <strong>{$trader->getFullName()}</strong> a bien été ajouter.");

            $this->manager->persist($trader);
            $this->manager->flush();
            return $this->redirectToRoute('trader');
        }
        return $this->render('trader/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Trader $trader
     * @return Response
     * @Route("/traders/{id}/edit", name="trader_edit")
     */
    public function edit(Request $request, Trader $trader):Response
    {
        $user = $this->getUser();
        $form = $this->createForm(TraderType::class, $trader);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le commercial <strong>{$trader->getFullName()}</strong> a bien été modifier.");
            $this->manager->persist($trader);
            $this->manager->flush();
            return $this->redirectToRoute('trader');
        }
        return $this->render('trader/edit.html.twig', [
            'form' => $form->createView(),
            'trader' => $trader,
        ]);
    }

    /**
     * @param Trader $trader
     * @return Response
     * @Route("/traders/{id}/delete", name="trader_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Trader $trader):Response
    {
        $this->addFlash('success', "Le commercial <strong>{$trader->getFullName()}</strong> a bien été supprimer.");
        $this->manager->remove($trader);
        $this->manager->flush();
        return $this->redirectToRoute('trader');
    }

    /**
     * @param Trader $trader
     * @return Response
     * @Route("/trader/{id}/show", name="trader_show")
     *
     */
    public function show(Trader $trader):Response
    {
        return $this->render('trader/show.html.twig', [
            'trader' => $trader,
        ]);
    }
}
