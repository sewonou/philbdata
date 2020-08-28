<?php

namespace App\Controller;

use App\Entity\Balance;
use App\Form\BalanceType;
use App\Repository\BalanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BalanceController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, BalanceRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/balances", name="balance")
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function index():Response
    {
        $balances = $this->repository->findAll();
        return $this->render('balance/index.html.twig', [
            'balances' => $balances,
        ]);
    }
    /**
     * @param Request $request
     * @return Response
     * @Route("/balances/add", name="balance_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request):Response
    {
        $user= $this->getUser();
        $balance = new Balance();
        $form = $this->createForm(BalanceType::class, $balance);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $balance->setAuthor($user);
            $this->manager->persist($balance);
            $this->manager->flush();
            $this->addFlash('success', "Le solde <strong>{$balance->getId()}</strong> a bien été ajouter");
            return $this->redirectToRoute('balance');
        }
        return $this->render('balance/create.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param Balance $balance
     * @return Response
     * @Route("/balances/{id}/edit", name="balance_edit")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function edit(Request $request, Balance $balance):Response
    {
        $user= $this->getUser();
        $form = $this->createForm(BalanceType::class, $balance);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $balance->setAuthor($user);
            $this->manager->persist($balance);
            $this->manager->flush();
            $this->addFlash('success', "Le solde <strong>{$balance->getId()}</strong> a bien été modifier");
            return $this->redirectToRoute('balance');
        }
        return $this->render('balance/create.html.twig', [
            'form' => $form->createView(),
            'balance' => $balance,
        ]);
    }
    /**
     * @param Balance $balance
     * @return Response
     * @Route("/balance/{id}/delete}", name="balance_delete")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(Balance $balance):Response
    {
        $this->addFlash('success', "Le solde <strong>{$balance->getId()}</strong> a bien été supprimer");
        $this->manager->remove($balance);
        $this->manager->flush();
        return  $this->redirectToRoute('balance');
    }
}
