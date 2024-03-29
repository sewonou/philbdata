<?php

namespace App\Controller;

use App\Entity\Prefecture;
use App\Entity\Search;
use App\Form\PrefectureType;
use App\Form\SearchType;
use App\Repository\PointofsaleRepository;
use App\Repository\PrefectureRepository;
use App\Service\SimCardStat;
use App\Service\ZoningSaleStat;
use App\Service\ZoningStat;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrefectureController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, PrefectureRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/prefectures", name="prefecture")
     * @param ZoningStat $zoningStat
     * @param SimCardStat $simCardStat
     * @return Response
     */
    public function index(ZoningStat $zoningStat, SimCardStat $simCardStat):Response
    {
        return $this->render('prefecture/index.html.twig', [
            'prefectures'=>$this->repository->findAll(),
            'zoningStat' => $zoningStat,
            'simCardStat' => $simCardStat,
        ]);
    }

    /**
     * @Route("/prefectures/add", name="prefecture_add")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request):Response
    {

        $prefecture = new Prefecture();
        $form = $this->createForm(PrefectureType::class, $prefecture);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->addFlash('success', "La préfecture <strong>{$prefecture->getName()}</strong> a bien été ajouter.");
            $this->manager->persist($prefecture);
            $this->manager->flush();
            return  $this->redirectToRoute('prefecture');
        }

        return $this->render('prefecture/add.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/prefectures/{id}/edit}", name="prefecture_edit")
     * @param Request $request
     * @param Prefecture $prefecture
     * @return Response
     */
    public function edit(Request $request, Prefecture $prefecture):Response
    {

        $form = $this->createForm(PrefectureType::class, $prefecture);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->addFlash('success', "La préfecture <strong>{$prefecture->getName()}</strong> a bien été modifier.");
            $this->manager->persist($prefecture);
            $this->manager->flush();
            return  $this->redirectToRoute('prefecture');
        }

        return $this->render('prefecture/edit.html.twig', [
            'form'=>$form->createView(),
            'prefecture'=>$prefecture,
        ]);
    }

    /**
     * @Route("/prefectures/{id}/delete", name="prefecture_delete")
     * @param Prefecture $prefecture
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Prefecture $prefecture):Response
    {
        $this->addFlash('success', "La préfecture <strong>{$prefecture->getName()}</strong> a bien été supprimer.");
        $this->manager->remove($prefecture);
        $this->manager->flush();

        return $this->redirectToRoute('prefecture');
    }

    /**
     * @Route("/prefectures/{id}/show", name="prefecture_show")
     * @param Prefecture $prefecture
     * @param Request $request
     * @param PointofsaleRepository $pointofsaleRepository
     * @param ZoningSaleStat $zoningStat
     * @return Response
     */
    public function show(Prefecture $prefecture, Request $request, PointofsaleRepository $pointofsaleRepository, ZoningSaleStat $zoningStat):Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        $pointofsales = $pointofsaleRepository->findInPrefecture($prefecture->getId());
        $lastSale = $zoningStat->getSaleInPrefectureWithLimit($prefecture->getId(), 1);
        $sales = $zoningStat->getSaleInPrefectureWithLimit($prefecture->getId(), 8);
        $percentWeekCom = $zoningStat->getLastWeekCommission($sales);
        $periodSales = $zoningStat->getSaleInPrefectureByDay($search, $prefecture->getId());
        $stat = $zoningStat->getSaleInPrefecture($search, $prefecture->getId());
        return  $this->render('prefecture/show.html.twig', [
            'prefecture'=>$prefecture,
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
