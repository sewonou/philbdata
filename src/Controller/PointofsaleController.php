<?php

namespace App\Controller;

use App\Entity\Pointofsale;
use App\Entity\Search;
use App\Form\PointofsaleType;
use App\Form\SearchType;
use App\Repository\PointofsaleRepository;
use App\Service\PointofsaleStat;
use App\Service\SimCardStat;
use App\Service\ZoningStat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PointofsaleController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, PointofsaleRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/pointofsales", name="pointofsale")
     * @param SimCardStat $simCardStat
     * @return Response
     */
    public function index(SimCardStat $simCardStat):Response
    {
        $pointofsales = $this->repository->findPointofsaleWithTrader(true);
        return $this->render('pointofsale/index.html.twig', [
            'pointofsales' => $pointofsales,
            'simCardStat' => $simCardStat,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/pointofsales/add", name="pointofsale_add")
     */
    public function create(Request $request):Response
    {
        $pointofsale = new Pointofsale();
        $form = $this->createForm(PointofsaleType::class, $pointofsale);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($pointofsale);
            $this->manager->flush();
            $this->addFlash('success', "Le Point de vente <strong>{$pointofsale->getName()}</strong> a bien été ajouter");
            return $this->redirectToRoute('pointofsale');
        }
        return  $this->render('pointofsale/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Pointofsale $pointofsale
     * @return Response
     * @Route("/pointofsales/{id}/edit", name="pointofsale_edit")
     */
    public function edit(Request $request, Pointofsale $pointofsale):Response
    {

        $form = $this->createForm(PointofsaleType::class, $pointofsale);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($pointofsale);
            $this->manager->flush();
            $this->addFlash('success', "Le Point de vente <strong>{$pointofsale->getName()}</strong> a bien été modifier");
            return $this->redirectToRoute('pointofsale');
        }
        return  $this->render('pointofsale/form.html.twig', [
            'form' => $form->createView(),
            'pointofsale' => $pointofsale
        ]);
    }

    /**
     * @param Pointofsale $pointofsale
     * @return Response
     * @Route("/pointofsales/{id}/delete", name="pointofsale_delete")
     */
    public function delete(Pointofsale $pointofsale):Response
    {
        $this->addFlash('success', "Le Point de vente <strong>{$pointofsale->getName()}</strong> a bien été supprimer");
        $this->manager->remove($pointofsale);
        $this->manager->flush();
        return $this->redirectToRoute('pointofsale');
    }

    /**
     * @param Pointofsale $pointofsale
     * @param Request $request
     * @param ZoningStat $zoningStat
     * @param PointofsaleStat $pointofsaleStat
     * @return Response
     * @Route("/pointofsales/{id}/show", name="pointofsale_show")
     */
    public function show(Pointofsale $pointofsale, Request $request,ZoningStat $zoningStat, PointofsaleStat $pointofsaleStat):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $lastSale = $zoningStat->getSaleInPointofsaleWithLimit($pointofsale->getId(), 1);
        $sales = $zoningStat->getSaleInPointofsaleWithLimit($pointofsale->getId(), 8);
        $percentWeekCom = $zoningStat->getLastWeekCommission($sales);
        $periodSales = $zoningStat->getSaleInPointofsaleByDay($search, $pointofsale->getId());
        $stat = $zoningStat->getSaleInPointofsale($search, $pointofsale->getId());
        $giveReceives = $pointofsaleStat->getGiveReceivedByPos($search, $pointofsale->getId());
        $giveSends = $pointofsaleStat->getGiveSendByPos($search, $pointofsale->getId());
        return  $this->render('pointofsale/show.html.twig', [
            'pointofsale' => $pointofsale,
            'form'=>$form->createView(),
            'lastSale'=> $lastSale,
            'sales' => $sales,
            'percentWeekComm' => $percentWeekCom,
            'periodSales' => $periodSales,
            'stat' => $stat,
            'giveReceives' => $giveReceives,
            'giveSends' => $giveSends,
        ]);
    }
}
