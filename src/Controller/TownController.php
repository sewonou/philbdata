<?php

namespace App\Controller;

use App\Entity\Search;
use App\Entity\Town;
use App\Form\SearchType;
use App\Form\TownType;
use App\Repository\PointofsaleRepository;
use App\Repository\TownRepository;
use App\Service\SimCardStat;
use App\Service\ZoningSaleStat;
use App\Service\ZoningStat;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TownController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, TownRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/towns", name="town")
     * @param ZoningStat $zoningStat
     * @param SimCardStat $simCardStat
     * @return Response
     */
    public function index(ZoningStat $zoningStat, SimCardStat $simCardStat):Response
    {
        return $this->render('town/index.html.twig', [
            'towns'=>$this->repository->findAll(),
            'zoningStat' => $zoningStat,
            'simCardStat' => $simCardStat
        ]);
    }

    /**
     * @Route("/towns/add", name="town_add")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request):Response
    {

        $town = new Town();
        $form = $this->createForm(TownType::class, $town);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->addFlash('success', "La commune <strong>{$town->getName()}</strong> a bien été ajouter.");
            $this->manager->persist($town);
            $this->manager->flush();
            return  $this->redirectToRoute('town');
        }

        return $this->render('town/add.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/towns/{id}/edit}", name="town_edit")
     * @param Request $request
     * @param Town $town
     * @return Response
     */
    public function edit(Request $request, Town $town):Response
    {
        $form = $this->createForm(TownType::class, $town);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->addFlash('success', "La commune <strong>{$town->getName()}</strong> a bien été modifier.");
            $this->manager->persist($town);
            $this->manager->flush();
            return  $this->redirectToRoute('town');
        }

        return $this->render('town/edit.html.twig', [
            'form'=>$form->createView(),
            'town'=>$town,
        ]);
    }

    /**
     * @Route("/towns/{id}/delete", name="town_delete")
     * @param Town $town
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Town $town):Response
    {
        $this->addFlash('success', "La commune <strong>{$town->getName()}</strong> a bien été supprimer.");
        $this->manager->remove($town);
        $this->manager->flush();

        return $this->redirectToRoute('town');
    }

    /**
     * @Route("/towns/{id}/show", name="town_show")
     * @param Town $town
     * @param Request $request
     * @param PointofsaleRepository $pointofsaleRepository
     * @param ZoningSaleStat $zoningStat
     * @return Response
     */
    public function show(Town $town, Request $request, PointofsaleRepository $pointofsaleRepository, ZoningSaleStat $zoningStat):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $pointofsales = $pointofsaleRepository->findInTown($town->getId());
        $lastSale = $zoningStat->getSaleInTownWithLimit($town->getId(), 1);
        $sales = $zoningStat->getSaleInTownWithLimit($town->getId(), 8);
        $percentWeekCom = $zoningStat->getLastWeekCommission($sales);
        $periodSales = $zoningStat->getSaleInTownByDay($search, $town->getId());
        $stat = $zoningStat->getSaleInTown($search, $town->getId());
        return  $this->render('town/show.html.twig', [
            'town'=>$town,
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
