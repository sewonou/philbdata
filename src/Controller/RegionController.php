<?php

namespace App\Controller;

use App\Entity\Region;
use App\Entity\Search;
use App\Form\RegionType;
use App\Form\SearchType;
use App\Repository\PointofsaleRepository;
use App\Repository\RegionRepository;
use App\Service\SimCardStat;
use App\Service\ZoningSaleStat;
use App\Service\ZoningStat;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegionController extends AbstractController
{
    private $manager;

    private $repository;

    public function __construct(EntityManagerInterface $manager, RegionRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }


    /**
     * @Route("/regions", name="region")
     * @param ZoningStat $zoningStat
     * @param SimCardStat $simCardStat
     * @return Response
     */
    public function index(ZoningStat $zoningStat, SimCardStat $simCardStat)
    {
        return $this->render('region/index.html.twig', [
            'regions' => $this->repository->findAll(),
            'zoningStat' => $zoningStat,
            'simCardStat' => $simCardStat,
        ]);
    }

    /**
     * @Route("/regions/add", name="region_add")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request):Response
    {

        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $this->addFlash('success', "La région <strong>{$region->getName()}</strong> a bien été ajouter.");
            $this->manager->persist($region);
            return  $this->redirectToRoute('region');
        }

        return  $this->render('region/add.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/regions/{id}/edit", name="region_edit")
     * @param Request $request
     * @param Region $region
     * @return Response
     */
    public function edit(Request $request, Region $region):Response
    {

        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $this->addFlash('success', "La région <strong>{$region->getName()}</strong> a bien été modifier.");
            $this->manager->persist($region);
            return  $this->redirectToRoute('region');
        }

        return  $this->render('region/edit.html.twig', [
            'form'=> $form->createView(),
            'region' => $region,
        ]);
    }

    /**
     * @Route("/regions/{id}/delete", name="region_delete")
     * @param Region $region
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Region $region):Response
    {
        $this->addFlash('success', "La région <strong>{$region->getName()}</strong> a bien été supprimer.");
        $this->manager->remove($region);
        $this->manager->flush();
        return $this->redirectToRoute('region');
    }

    /**
     * @Route("/regions/{id}/show", name="region_show")
     * @param Region $region
     * @param Request $request
     * @param PointofsaleRepository $pointofsaleRepository
     * @param ZoningSaleStat $zoningStat
     * @return Response
     */
    public function show(Region $region,Request $request, PointofsaleRepository $pointofsaleRepository, ZoningSaleStat $zoningStat):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $pointofsales = $pointofsaleRepository->findInRegion($region->getId());
        $lastSale = $zoningStat->getSaleInRegionWithLimit($region->getId(), 1);
        $sales = $zoningStat->getSaleInRegionWithLimit($region->getId(), 8);
        $percentWeekCom = $zoningStat->getLastWeekCommission($sales);
        $periodSales = $zoningStat->getSaleInRegionByDay($search, $region->getId());
        $stat = $zoningStat->getSaleInRegion($search, $region->getId());
        return $this->render('region/show.html.twig', [
            'region'=> $region,
            'form' => $form->createView(),
            'pointofsales' => $pointofsales,
            'lastSale'=> $lastSale,
            'sales' => $sales,
            'percentWeekComm' => $percentWeekCom,
            'periodSales' => $periodSales,
            'stat' => $stat,
        ]);
    }
}
