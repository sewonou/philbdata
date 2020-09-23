<?php

namespace App\Controller;

use App\Entity\Search;
use App\Form\SearchType;
use App\Repository\SaleRepository;
use App\Service\PointofsaleStat;
use App\Service\SimCardStat;
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
     * @return Response
     */
    public function index(SaleRepository $saleRepository, SimCardStat $simCardStat):Response
    {
        $sales = $saleRepository->findSaleByDate();
        return $this->render('dashboard/index.html.twig', [
            'sales'=>$sales,
            'simCardStat' => $simCardStat,
        ]);
    }

    /**
     * @Route("/periodic/stats", name="periodicBoard")
     * @param Request $request
     * @param PointofsaleStat $pointofsaleStat
     * @return Response
     * @throws \Exception
     */
    public function periodicBoard(Request $request, PointofsaleStat $pointofsaleStat):Response
    {
        $search = new Search();
        $search->setStartAt(new \DateTime('-1 day'))
            ->setEndAt(new \DateTime('-1 day'))
        ;
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $pointofsales = $pointofsaleStat->getPointofsalesPeriodInput($search);
        return $this->render('dashboard/periodicBoard.html.twig', [
            'form' => $form->createView(),
            'pointofsales' => $pointofsales,
        ]);
    }
}
