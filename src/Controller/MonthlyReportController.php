<?php

namespace App\Controller;

use App\Entity\MonthlyReport;
use App\Entity\Search;
use App\Form\MonthlyReportType;
use App\Form\SearchType;
use App\Repository\MonthlyReportRepository;
use App\Repository\TraderRepository;
use App\Service\TraderStat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonthlyReportController extends AbstractController
{
    private $repository;
    private $manager;
    private $traderRepository;

    public function __construct(EntityManagerInterface $manager, MonthlyReportRepository $repository, TraderRepository $traderRepository)
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->traderRepository = $traderRepository;
    }

    /**
     * @Route("/monthly/report", name="monthly_report")
     * @param TraderStat $traderStat
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(TraderStat $traderStat, Request $request):Response
    {
        $traders =$this->traderRepository->findBy(['isTrader'=>true, 'isActive'=>true],['fullName'=>'ASC'],null, null);
        $search = new Search();
        $search->setStartAt(new \DateTime('-1 day'))
            ->setEndAt(new \DateTime('-1 day'))
        ;
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        return $this->render('monthly_report/index.html.twig', [
            'traders'=>$traders,
            'search' => $search,
            'form' => $form->createView(),
            'traderStat' => $traderStat,
        ]);
    }

    public function create(Request $request)
    {
        $monthly = new MonthlyReport();
        $form = $this->createForm(MonthlyReportType::class, $monthly);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'Le reporting mensuel à été ajouter avec succès');
            $this->manager->persist($monthly);
            $this->manager->flush();
            return $this->redirectToRoute('monthly_report');
        }
        return $this->render('monthly_report/form.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    public function edit(Request $request, MonthlyReport $monthly)
    {
        $form = $this->createForm(MonthlyReportType::class, $monthly);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'Le reporting mensuel à été ajouter avec succès');
            $this->manager->persist($monthly);
            $this->manager->flush();
            return $this->redirectToRoute('monthly_report');
        }
        return $this->render('monthly_report/form.html.twig', [
            'form'=>$form->createView(),
            'monthly'=>$monthly,
        ]);
    }

    public function delete(MonthlyReport $monthly)
    {
        $this->addFlash('success', "Le report a bien été supprimer");
        $this->manager->remove($monthly);
        $this->manager->flush();
        return $this->redirectToRoute('monthly_report');
    }
}
