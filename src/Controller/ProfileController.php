<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use App\Repository\ProfileRepository;
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
     */
    public function index():Response
    {
        $profiles = $this->repository->findAll();
        return $this->render('profile/index.html.twig', [
            'profiles'=>$profiles,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/profiles/add", name="profile_add")
     */
    public function create(Request $request):Response
    {
        $user = $this->getUser();
        $profile = new Profile();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le profil <strong>{$profile->getTitle()}</strong> a bien été ajouter");
            $profile->setAuthor($user);
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
     * @Route("/profiles/{id}/edit", name="profile")
     */
    public function edit(Request $request, Profile $profile):Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "Le profil <strong>{$profile->getTitle()}</strong> a bien été ajouter");
            $profile->setAuthor($user);
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
     * @Route("/profiles/{id}/delete", name="delete")
     */
    public function delete(Profile $profile):Response
    {
        $this->addFlash('success', "Le profile <strong>{$profile->getTitle()}</strong> a bien été supprimer");
        $this->manager->remove($profile);
        $this->manager->flush();
        return $this->redirectToRoute('profile');
    }
}
