<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Form\ZoneType;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZoneController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, ZoneRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/zones", name="zone")
     */
    public function index():Response
    {
        return $this->render('zone/index.html.twig', [
            'zones' => $this->repository->findAll(),
        ]);
    }

    /**
     * @Route("/zones/add", name="zone_add")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request):Response
    {

        $zone = new  Zone();
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "La zone <strong>{$zone->getName()}</strong> a bien été ajouter");
            $this->manager->persist($zone);
            $this->manager->flush();
            return $this->redirectToRoute('zone');
        }

        return $this->render('zone/add.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/zones/{id}/edit", name="zone_edit")
     * @param Request $request
     * @param Zone $zone
     * @return Response
     */
    public function edit(Request $request, Zone $zone):Response
    {

        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "La zone <strong>{$zone->getName()}</strong> a bien été modifier");
            $this->manager->persist($zone);
            $this->manager->flush();
            return $this->redirectToRoute('zone');
        }

        return $this->render('zone/edit.html.twig', [
            'form'=>$form->createView(),
            'zone'=>$zone,
        ]);
    }

    /**
     * @Route("/zones/{id}/delete", name="zone_delete")
     * @param Zone $zone
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Zone $zone):Response
    {
        $this->addFlash('success', "La zone <strong>{$zone->getName()}</strong> a bien été supprimer ");
        $this->manager->remove($zone);
        $this->manager->flush();
        return $this->redirectToRoute('zone');
    }

    /**
     *  @Route("/zones/{id}/show", name="zone_show")
     * @param Zone $zone
     * @return Response
     */
    public function show(Zone $zone):Response
    {
        return $this->render('zone/show.html.twig', [
            'zone' => $zone
        ]);
    }
}
