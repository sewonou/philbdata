<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, TypeRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/types", name="type")
     * @param Request $request
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function index(Request $request):Response
    {
        $types = $this->repository->findAll();
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($type);
            $this->manager->flush();
            $this->addFlash('success', "Le type de transaction <strong>{$type->getTitle()}</strong> a bien été ajouter");
            return $this->redirectToRoute('type');
        }
        return $this->render('type/index.html.twig', [
            'form' => $form->createView(),
            'types' => $types,
        ]);
    }

    /**
     * @Route("/types/{id}/edit", name="type_edit")
     * @param Request $request
     * @param Type $type
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function edit(Request $request, Type $type):Response
    {
        $types = $this->repository->findAll();
        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($type);
            $this->manager->flush();
            $this->addFlash('success', "Le type de transaction <strong>{$type->getTitle()}</strong> a bien été modifier");
            return $this->redirectToRoute('type');
        }
        return $this->render('type/index.html.twig', [
            'form' => $form->createView(),
            'types' => $types,
            'type' => $type,
        ]);
    }

    /**
     * @Route("/types/{id}/delete", name="type_delete")
     * @param Type $type
     * @return Response
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(Type $type):Response
    {
        $this->addFlash('success', "Le type de transaction <strong>{$type->getTitle()}</strong> a bien été supprimer");
        $this->manager->remove($type);
        $this->manager->flush();
        return $this->redirectToRoute('type');
    }
}
