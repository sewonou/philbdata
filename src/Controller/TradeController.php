<?php

namespace App\Controller;

use App\Entity\Trade;
use App\Form\TradeType;
use App\Repository\TradeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TradeController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, TradeRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/trades", name="trade")
     * @return Response
     */
    public function index():Response
    {
        $trades = $this->repository->findAll();
        return $this->render('trade/index.html.twig', [
            'trades' => $trades,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/trades/add", name="trade_add")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function create(Request $request):Response
    {
        $user = $this->getUser();
        $trade = new Trade();
        $form = $this->createForm(TradeType::class, $trade);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $trade->setAuthor($user);
            $this->manager->persist($trade);
            $this->manager->flush();
            $this->addFlash('success', "La transaction <strong>{$trade->getRefId()}</strong> a bien été ajouter");
            return $this->redirectToRoute('trade');
        }
        return $this->render('trade/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Trade $trade
     * @return Response
     * @Route("/trades/{id}/edit", name="trade_edit")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function edit(Request $request, Trade $trade):Response
    {
        $user = $this->getUser();
        $form = $this->createForm(TradeType::class, $trade);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $trade->setAuthor($user);
            $this->manager->persist($trade);
            $this->manager->flush();
            $this->addFlash('success', "La transaction <strong>{$trade->getRefId()}</strong> a bien été modifier");
            return $this->redirectToRoute('trade');
        }
        return $this->render('trade/create.html.twig', [
            'form' => $form->createView(),
            'trade' => $trade
        ]);
    }

    /**
     * @param Trade $trade
     * @return Response
     * @Route("/trades/{id}/delete", name="trade_delete")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(Trade $trade):Response
    {
        $this->addFlash('success', "La transaction <strong>{$trade->getRefId()}</strong> a bien été supprimer");
        $this->manager->remove($trade);
        $this->manager->flush();
        return $this->redirectToRoute('trade');
    }
}
