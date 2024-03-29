<?php

namespace App\Controller;

use App\Entity\Search;
use App\Form\SearchType;
use App\Repository\PointofsaleRepository;
use App\Repository\SaleRepository;
use App\Service\agentStat;
use App\Service\PointofsaleStat;
use App\Service\SimCardStat;
use App\Service\TradeStat;
use App\Service\ZoningStat;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="homePage")
     * @param SaleRepository $saleRepository
     * @param SimCardStat $simCardStat
     * @param PointofsaleStat $pointofsaleStat
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(SaleRepository $saleRepository, SimCardStat $simCardStat, PointofsaleStat $pointofsaleStat):Response
    {
        $lastDaySale = $saleRepository->findSaleByDateLimit(1);
        $sales = $saleRepository->findSaleByDateLimit(8);
        $percentWeekComm = $pointofsaleStat->getLastWeekCommission(8);

        return $this->render('dashboard/index.html.twig', [
            'sales'=>$sales,
            'simCardStat' => $simCardStat,
            'lastSale' => $lastDaySale,
            'percentWeekComm' => $percentWeekComm,
        ]);
    }

    /**
     * @Route("/boards/active_pointofsales", name="activePointofsaleBoard")
     * @param Request $request
     * @param PointofsaleStat $pointofsaleStat
     * @return Response
     * @throws \Exception
     * @IsGranted("ROLE_ADMIN")
     */
    public function activePointofsaleBoard(Request $request, PointofsaleStat $pointofsaleStat):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $sales = $pointofsaleStat->getAllPointofsalesPeriodInput($search);
        $goal = $pointofsaleStat->getPointofsaleGoal($search);
        return $this->render('dashboard/activePointofsaleBoard.html.twig', [
            'form' => $form->createView(),
            'sales' => $sales,
            'goal' => $goal,
        ]);
    }

    /**
     * @Route("/boards/inactive_pointofsales", name="inactivePointofsaleBoard")
     * @param Request $request
     * @param PointofsaleStat $pointofsaleStat
     * @param PointofsaleRepository $pointofsaleRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function inactivePointofsaleBoard(Request $request, PointofsaleStat $pointofsaleStat, PointofsaleRepository $pointofsaleRepository):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $pointofsales = $pointofsaleStat->getInactivePointofsales($pointofsaleStat->getAllPointofsalesPeriodInput($search));
        $goal = $pointofsaleStat->getPointofsaleGoal($search);
        return $this->render('dashboard/inactivePointofsalesBoard.html.twig', [
            'form' => $form->createView(),
            'pointofsales' => $pointofsales,
            'pointofsaleStat' => $pointofsaleStat,
            'goal' => $goal,
            'search'=> $search,
        ]);
    }


    /**
     * @Route("/boards/finance_stats", name="financeBoard")
     * @param TradeStat $tradeStat
     * @param PointofsaleStat $pointofsaleStat
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function financeBoard(TradeStat $tradeStat ,PointofsaleStat $pointofsaleStat, Request $request):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $sales = $pointofsaleStat->getSaleByDay($search);
        $giveComs = $pointofsaleStat->getGiveComByDay($search);
        $giveInByTraders = $tradeStat->getGiveInBankByTraders($search);
        $giveOutByTraders = $tradeStat->getGiveOutBankByTraders($search);
        $giveInByPointofsales = $tradeStat->getGiveInBankByPointofsales($search);
        $giveOutByPointofsales = $tradeStat->getGiveOutBankByPointofsales($search);
        $giveInBankByTradersByDay = $tradeStat->getGiveInBankByTradersByDay($search);
        $giveOutBankByTradersByDay = $tradeStat->getGiveOutBankByTradersByDay($search);
        $giveInBankByPointofsalesByDay = $tradeStat->getGiveInBankByPointofsalesByDay($search);
        $giveOutBankByPointofsalesByDay = $tradeStat->getGiveOutBankByPointofsalesByDay($search);
        $giveInBankByDay = $tradeStat->getGiveInBankByDay($search);
        $giveOutBankByDay = $tradeStat->getGiveOutBankByDay($search);
        $giveInBank = $tradeStat->getGiveInBank($search);
        $giveOutBank = $tradeStat->getGiveOutBank($search);
        $giveByTradersByDays = $tradeStat->getSaleByTradersByDay($search, 'CAGNT');

        return $this->render('dashboard/financeBoard.html.twig', [
            'form' => $form->createView(),
            'sales' => $sales,
            'giveComs' => $giveComs,
            'giveInBankByTraders' => $giveInByTraders,
            'giveOutBankByTraders' => $giveOutByTraders,
            'giveInBankByPointofsales' => $giveInByPointofsales,
            'giveOutBankByPointofsales' =>$giveOutByPointofsales,
            'giveInBankByTradersByDay' => $giveInBankByTradersByDay,
            'giveOutBankByTradersByDay' => $giveOutBankByTradersByDay,
            'giveInBankByPointofsalesByDay' => $giveInBankByPointofsalesByDay,
            'giveOutBankByPointofsalesByDay' => $giveOutBankByPointofsalesByDay,
            'giveInBankByDay'=> $giveInBankByDay,
            'giveOutBankByDay'=> $giveOutBankByDay,
            'giveInBank'=> $giveInBank,
            'giveOutBank'=> $giveOutBank,
            'giveByTradersByDays' => $giveByTradersByDays,
        ]);
    }

    /**
     * @Route("/boards/region_stats", name="regionBoard")
     * @param PointofsaleStat $pointofsaleStat
     * @param Request $request
     * @param ZoningStat $zoningStat
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function regionBoard(PointofsaleStat $pointofsaleStat, Request $request, ZoningStat $zoningStat):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $sales = $pointofsaleStat->getSaleByRegion($search);
        $others = $pointofsaleStat->getSaleInRegionForPointofsaleWithoutDistrict($search);


        return $this->render('dashboard/regionBoard.html.twig', [
            'form' => $form->createView(),
            'sales' => $sales,
            'others' => $others,
            'zoningStat' => $zoningStat
        ]);
    }

    /**
     * @Route("/boards/sales_comparaison", name="comparaisonBoard")
     * @param PointofsaleStat $pointofsaleStat
     * @param Request $request
     * @param ZoningStat $zoningStat
     * @return Response
     */
    public function ComparisonBoard(PointofsaleStat $pointofsaleStat, Request $request, ZoningStat $zoningStat)
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $sales = $pointofsaleStat->getSaleByDay($search);
        return $this->render('dashboard/comparisonBoard.html.twig', [
            'form' => $form->createView(),
            'sales' => $sales,
        ]);
    }

    /**
     * @Route("/boards/periodic_stats", name="periodicBoard")
     * @param Request $request
     * @param TradeStat $tradeStat
     * @param PointofsaleStat $pointofsaleStat
     * @return Response
     */
    public function periodicBoard(Request $request, TradeStat $tradeStat, PointofsaleStat $pointofsaleStat):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $saleByTraders = $tradeStat->getSaleByTraders($search, 'CAGNT');
        $commissionByAGNT ='' ;
        //$saleByTradersByDays = $tradeStat->getSaleByTradersByDay($search, 'CAGNT');
        return $this->render('dashboard/periodicBoard.html.twig', [
            'form' => $form->createView(),
            'saleByTraders' => $saleByTraders,
            'pointofsaleStat' => $pointofsaleStat,
            'tradeStat' => $tradeStat,
            'search' => $search,
        ]);
    }

    /**
     * @Route("/boards/agent_board", name="agentBoard")
     * @param Request $request
     * @param agentStat $agentStat
     * @param PointofsaleRepository $pointofsaleRepository
     * @return Response
     */
    public function agentBoard(Request $request, agentStat $agentStat, PointofsaleRepository $pointofsaleRepository):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $pointofsales = $pointofsaleRepository->findPointofsaleByProfile(true, 'AGNT');

        return $this->render('dashboard/agentBoard.html.twig', [
            'form' => $form->createView(),
            'pointofsales' => $pointofsales,
            'agentStat' => $agentStat,
            'search' => $search,
        ]);
    }
    
    
}
