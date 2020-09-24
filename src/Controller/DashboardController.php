<?php

namespace App\Controller;

use App\Entity\Search;
use App\Form\SearchType;
use App\Repository\PointofsaleRepository;
use App\Repository\SaleRepository;
use App\Service\PointofsaleStat;
use App\Service\SimCardStat;
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
     * @Route("/periodic/stats", name="periodicBoard")
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
        $sales = $pointofsaleStat->getPointofsalesPeriodInput($search);
        $goal = $pointofsaleStat->getPointofsaleGoal($search);
        return $this->render('dashboard/periodicBoard.html.twig', [
            'form' => $form->createView(),
            'sales' => $sales,
            'goal' => $goal,
        ]);
    }

    /**
     * @Route("/pointofsales/performance", name="pointofsaleBoard")
     * @param Request $request
     * @param PointofsaleStat $pointofsaleStat
     * @param PointofsaleRepository $pointofsaleRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function pointofsaleBoard(Request $request, PointofsaleStat $pointofsaleStat, PointofsaleRepository $pointofsaleRepository):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $pointofsales = $pointofsaleRepository->findBy(['isActive'=>true]);
        $sales = $pointofsaleStat->getPointofsalesPeriodInput($search);
        $goal = $pointofsaleStat->getPointofsaleGoal($search);
        return $this->render('dashboard/performanceBoard.html.twig', [
            'form' => $form->createView(),
            'pointofsales' => $pointofsales,
            'pointofsaleStat' => $pointofsaleStat,
            'goal' => $goal,
            'search'=> $search,
        ]);
    }
}
