<?php

namespace App\Controller;

use App\Entity\Search;
use App\Entity\Trader;
use App\Form\SearchType;
use App\Form\TraderType;
use App\Repository\TraderRepository;
use App\Service\PointofsaleStat;
use App\Service\TraderStat;
use App\Service\TradeStat;
use App\Service\ZoningStat;
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
     * @param TraderStat $traderStat
     * @return Response
     */
    public function index(TraderStat $traderStat):Response
    {
        $traders =$this->repository->findBy(['isTrader'=>true, 'isActive'=>true],['fullName'=>'ASC'],null, null);
        return $this->render('trader/index.html.twig', [
            'traders' => $traders,
            'traderStat' => $traderStat,
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
     * @param Request $request
     * @param TraderStat $traderStat
     * @param TradeStat $tradeStat
     * @return Response
     * @Route("/traders/{id}/show", name="trader_show")
     */
    public function show(Trader $trader, Request $request, TraderStat $traderStat, TradeStat $tradeStat):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $lastSale = $traderStat->getTraderInputByDayWithLimit($trader, 1);
        $sales = $traderStat->getTraderInputByDayWithLimit($trader, 8);
        $percentWeekCom = $traderStat->getLastWeekCommission($sales);
        $periodSales = $traderStat->getTraderInputByDay($trader, $search);
        $stat = $traderStat->getSaleForTrader($trader, $search);
        $saleByTrader = $tradeStat->getSaleByTrader($search, $trader);
        $saleByTraderByDays = $tradeStat->getSaleByTraderByDay($search, $trader);
        $virtualsToBankByTrader = $traderStat->getVirtualToBankByTrader($search, $trader);
        $virtualsFromBankToTrader = $traderStat->getVirtualFromBankToTrader($search, $trader);
        $virtualsToPosByTrader = $traderStat->getVirtualToPosByTrader($search, $trader);
        $virtualsFromPosToTrader = $traderStat->getVirtualFromPosToTrader($search, $trader);
        $virtualsToMasterByTrader = $traderStat->getVirtualToMasterByTrader($search, $trader);
        $virtualsFromMasterToTrader = $traderStat->getVirtualFromMasterToTrader($search, $trader);
        return $this->render('trader/show.html.twig', [
            'trader' => $trader,
            'form'=>$form->createView(),
            'lastSale'=> $lastSale,
            'sales' => $sales,
            'percentWeekComm' => $percentWeekCom,
            'periodSales' => $periodSales,
            'stat' => $stat,
            'saleByTrader' => $saleByTrader,
            'saleByTraderByDays'=>$saleByTraderByDays,
            'virtualsToBankByTrader' => $virtualsToBankByTrader,
            'virtualsFromBankToTrader' => $virtualsFromBankToTrader,
            'virtualsToPosByTrader' => $virtualsToPosByTrader,
            'virtualsFromPosToTrader' => $virtualsFromPosToTrader,
            'virtualsToMasterByTrader' => $virtualsToMasterByTrader,
            'virtualsFromMasterToTrader' => $virtualsFromMasterToTrader,
        ]);
    }

    /**
     *
     * @param TraderStat $traderStat
     * @param Request $request
     * @return Response
     * @Route("/traders/performance", name="trader_performance")
     * @throws \Exception
     */
    public function performanceBoard(TraderStat $traderStat, Request $request):Response
    {
        $search = new Search();
        $search->setStartAt(new \DateTime('-1 day'))
            ->setEndAt(new \DateTime('-1 day'))
        ;
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $traders =$this->repository->findBy(['isTrader'=>true, 'isActive'=>true],['fullName'=>'ASC'],null, null);
        return $this->render('trader/performanceBoard.html.twig', [
            'traders' => $traders,
            'search' => $search,
            'form' => $form->createView(),
            'traderStat' => $traderStat,
        ]);
    }

    /**
     * @Route("/traders/sales", name="trader_sales")
     * @param TraderStat $traderStat
     * @param TradeStat $tradeStat
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function salesBoard(TraderStat $traderStat,TradeStat $tradeStat, Request $request)
    {
        $search = new Search();
        $search->setStartAt(new \DateTime('-1 day'))
            ->setEndAt(new \DateTime('-1 day'))
        ;
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $traders =$this->repository->findBy(['isTrader'=>true, 'isActive'=>true],['fullName'=>'ASC'],null, null);
        return $this->render('trader/salesBoard.html.twig', [
            'traders' => $traders,
            'search' => $search,
            'form' => $form->createView(),
            'tradeStat' => $tradeStat,
            'traderStat' => $traderStat,
        ]);
    }
}
