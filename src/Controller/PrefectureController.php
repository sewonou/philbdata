<?php

namespace App\Controller;

use App\Entity\Prefecture;
use App\Form\PrefectureType;
use App\Repository\PrefectureRepository;
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
     */
    public function index():Response
    {
        return $this->render('prefecture/index.html.twig', [
            'prefectures'=>$this->repository->findAll(),
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
     * @return Response
     */
    public function show(Prefecture $prefecture):Response
    {
        return  $this->render('prefecture/show.html.twig', [
            'prefecture'=>$prefecture
        ]);
    }
}
