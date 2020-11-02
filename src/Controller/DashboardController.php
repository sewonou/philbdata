<?php

namespace App\Controller;

use App\Entity\Search;
use App\Form\SearchType;
use App\Repository\PointofsaleRepository;
use App\Repository\SaleRepository;
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
    public function periodicBoard(Request $request, PointofsaleStat $pointofsaleStat):Response
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

        return $this->render('dashboard/regionBoard.html.twig', [
            'form' => $form->createView(),
            'sales' => $sales,
            'zoningStat' => $zoningStat
        ]);
    }

    /**
     * @Route("/bords/sales_comparaison", name="comparaisonBoard")
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
}
