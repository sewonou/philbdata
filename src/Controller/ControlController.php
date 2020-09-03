<?php

namespace App\Controller;

use App\Entity\Control;
use App\Form\ControlType;
use App\Repository\ControlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ControlController extends AbstractController
{
    private $manager;
    private  $repository;

    public function __construct(EntityManagerInterface $manager, ControlRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/controls", name="control")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request) :Response
    {
        $control = new Control();
        $form = $this->createForm(ControlType::class, $control);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $oldControls = $this->repository->findBy(['pointofsale'=> $control->getPointofsale(), 'isActive'=>true]);
            if($oldControls){
                foreach ($oldControls as $oldControl){
                    $oldControl->setIsActive(false)
                        ->setEndAt( new  \DateTime());
                    $this->manager->persist($oldControl);
                }

            }
            $this->manager->persist($control);
            $this->manager->flush();
            $this->addFlash('success', "La relation entre Le <strong>{$control->getTrader()->getFullName()}</strong> et le Point de vente <strong>{$control->getPointofsale()->getName()}</strong> a bien été ajouter");
        }
        return $this->render('control/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/controls/{id]/edit", name="control_edit")
     * @param Request $request
     * @param Control $control
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, Control $control) :Response
    {
        $form = $this->createForm(ControlType::class, $control);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $oldControls = $this->repository->findBy(['pointofsale'=> $control->getPointofsale(), 'isActive'=>true]);
            if($oldControls){
                foreach ($oldControls as $oldControl){
                    $oldControl->setIsActive(false)
                        ->setEndAt( new  \DateTime());
                    $this->manager->persist($oldControl);
                }

            }
            $this->manager->persist($control);
            $this->manager->flush();
            $this->addFlash('success', "La relation entre Le <strong>{$control->getTrader()->getFullName()}</strong> et le Point de vente <strong>{$control->getPointofsale()->getName()}</strong> a bien été ajouter");
        }
        return $this->render('control/index.html.twig', [
            'form' => $form->createView(),
            'control' => $control,
        ]);
    }
}
