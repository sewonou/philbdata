<?php

namespace App\Controller;

use App\Repository\SaleRepository;
use App\Service\SimCardStat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
