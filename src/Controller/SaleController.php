<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Form\SaleType;
use App\Repository\SaleRepository;
use App\Service\PointofsaleStat;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaleController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, SaleRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/sales", name="sale")
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function index():Response
    {
        $sales = $this->repository->findSaleByMonth();

        $columnHeaders = $this->repository->findDistinctMonth();
        return $this->render('sale/index.html.twig', [
            'sales' => $sales,
            'columnHeaders' => $columnHeaders
        ]);
    }

    private function crossTab($date, $sales)
    {
        $saleTab = [];
        foreach ($date as $d){
            $saleTab[] = $d;
        }
        return $saleTab;
    }
    /**
     * @param Request $request
     * @return Response
     * @Route("/sales/add", name="sale_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request):Response
    {

        $sale = new Sale();
        $form = $this->createForm(SaleType::class, $sale);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $this->manager->persist($sale);
            $this->manager->flush();
            $this->addFlash('success', "La transaction <strong>{$sale->getRefId()}</strong> a bien été ajouter");
            return $this->redirectToRoute('sale');
        }
        return $this->render('sale/create.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param Sale $sale
     * @return Response
     * @Route("/sales/{id}/edit", name="sale_edit")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function edit(Request $request, Sale $sale):Response
    {

        $form = $this->createForm(SaleType::class, $sale);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $this->manager->persist($sale);
            $this->manager->flush();
            $this->addFlash('success', "La transaction <strong>{$sale->getRefId()}</strong> a bien été modifier");
            return $this->redirectToRoute('sale');
        }
        return $this->render('sale/create.html.twig', [
            'form' => $form->createView(),
            'sale' => $sale,
        ]);
    }

    /**
     * @param Sale $sale
     * @return Response
     * @Route("/sales/{id}/delete}", name="sale_delete")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(Sale $sale):Response
    {
        $this->addFlash('success', "La transaction <strong>{$sale->getRefId()}</strong> a bien été supprimer");
        $this->manager->remove($sale);
        $this->manager->flush();
        return  $this->redirectToRoute('sale');
    }




    /**
     * @param PointofsaleStat $pointofsaleStat
     * @param $date
     * @return Response
     * @Route("/admin/sales/{date}", name="sale_illegal")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function showIllegal(PointofsaleStat $pointofsaleStat, $date):Response
    {
        $sales = $pointofsaleStat->getIllegalSale($date);
        return $this->render('sale/illegalSale.html.twig', [
            'sales' => $sales,
        ]);
    }


    /**
     * @param Sale $sale
     * @return Response
     * @Route("/admin/sales/{id}", name="sale_show")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function show(Sale $sale):Response
    {

        return $this->render('sale/show.html.twig', [
            'sale' => $sale,
        ]);
    }
}
