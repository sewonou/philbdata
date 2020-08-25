<?php

namespace App\Controller;

use App\Entity\Region;
use App\Form\RegionType;
use App\Repository\RegionRepository;
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
     */
    public function index()
    {
        return $this->render('region/index.html.twig', [
            'regions' => $this->repository->findAll(),
        ]);
    }

    /**
     * @Route("/regions/add", name="region_add")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request):Response
    {
        $user = $this->getUser();
        $region = new Region();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $region->setAuthor($user);
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
        $user = $this->getUser();
        $form = $this->createForm(RegionType::class, $region);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $region->setAuthor($user);
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
     * @return Response
     */
    public function show(Region $region):Response
    {
        return $this->render('region/show.html.twig', [
            'region'=> $region,
        ]);
    }
}
