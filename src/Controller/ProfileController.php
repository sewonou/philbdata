<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Repository\ProfileRepository;
use App\Service\SimCardStat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, ProfileRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/profiles", name="profile")
     * @param SimCardStat $simCardStat
     * @return Response
     */
    public function index(SimCardStat $simCardStat):Response
    {
        $profiles = $this->repository->findAll();
        return $this->render('profile/index.html.twig', [
            'profiles'=>$profiles,
            'simCardStat' =>$simCardStat,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/profiles/add", name="profile_add")
     */
    public function create(Request $request):Response
    {

        $profile = new Profile();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le profil <strong>{$profile->getTitle()}</strong> a bien été ajouter");

            $this->manager->persist($profile);
            $this->manager->flush();
            $this->redirectToRoute('profile');
        }
        return $this->render('profile/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Profile $profile
     * @return Response
     * @Route("/profiles/{id}/edit", name="profile_edit")
     */
    public function edit(Request $request, Profile $profile):Response
    {

        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le profil <strong>{$profile->getTitle()}</strong> a bien été ajouter");
            $this->manager->persist($profile);
            $this->manager->flush();
            $this->redirectToRoute('profile');
        }
        return $this->render('profile/form.html.twig', [
            'form' => $form->createView(),
            'profile' => $profile,
        ]);
    }

    /**
     * @param Profile $profile
     * @return Response
     * @Route("/profiles/{id}/delete", name="profile_delete")
     */
    public function delete(Profile $profile):Response
    {
        $this->addFlash('success', "Le profile <strong>{$profile->getTitle()}</strong> a bien été supprimer");
        $this->manager->remove($profile);
        $this->manager->flush();
        return $this->redirectToRoute('profile');
    }
}
