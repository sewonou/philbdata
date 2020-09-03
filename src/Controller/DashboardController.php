<?php

namespace App\Controller;

use App\Repository\SaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="homePage")
     * @param SaleRepository $saleRepository
     * @return Response
     */
    public function index(SaleRepository $saleRepository):Response
    {
        $sales = $saleRepository->findSaleByDate();
        return $this->render('dashboard/index.html.twig', [
            'sales'=>$sales,
        ]);
    }
}
