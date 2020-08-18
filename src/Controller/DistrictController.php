<?php

namespace App\Controller;

use App\Entity\District;
use App\Form\DistrictType;
use App\Repository\DistrictRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DistrictController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, DistrictRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/districts", name="district")
     */
    public function index():Response
    {
        return $this->render('district/index.html.twig', [
            'districts'=>$this->repository->findAll(),
        ]);
    }

    /**
     * @Route("/districts/add", name="district_add")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request):Response
    {
        $district = new District();
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le quartier <strong>{$district->getName()}</strong> a bien été ajouter.");
            $this->manager->persist($district);
            $this->manager->flush();
            return  $this->redirectToRoute('district');
        }

        return $this->render('district/add.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/districts/{id}/edit}", name="district_edit")
     * @param Request $request
     * @param District $district
     * @return Response
     */
    public function edit(Request $request, District $district):Response
    {
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le quartier <strong>{$district->getName()}</strong> a bien été modifier.");
            $this->manager->persist($district);
            $this->manager->flush();
            return  $this->redirectToRoute('district');
        }

        return $this->render('district/edit.html.twig', [
            'form'=>$form->createView(),
            'district'=>$district,
        ]);
    }

    /**
     * @Route("/districts/{id}/delete", name="district_delete")
     * @param District $district
     * @return Response
     */
    public function delete(District $district):Response
    {
        $this->addFlash('success', "Le quartier <strong>{$district->getName()}</strong> a bien été supprimer.");
        $this->manager->remove($district);
        $this->manager->flush();

        return $this->redirectToRoute('district');
    }

    /**
     * @Route("/districts/{id}/show", name="district_show")
     * @param District $district
     * @return Response
     */
    public function show(District $district):Response
    {
        return  $this->render('district/show.html.twig', [
            'district'=>$district
        ]);
    }
}
