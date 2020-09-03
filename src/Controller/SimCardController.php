<?php

namespace App\Controller;

use App\Entity\SimCard;
use App\Form\SimCardType;
use App\Repository\ProfileRepository;
use App\Repository\SimCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SimCardController extends AbstractController
{
    private $manager;
    private $repository;

    public function __construct(EntityManagerInterface $manager, SimCardRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @Route("/simcards", name="sim_card")
     */
    public function index()
    {
        $simCards = $this->repository->findBy(['isActive'=>true], ['msisdn' => 'ASC'], null, null);
        return $this->render('sim_card/index.html.twig', [
            'simCards' => $simCards,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/simcards/add", name="sim_card_add")
     */
    public function create(Request $request):Response
    {
        $user = $this->getUser();
        $simCard  = new SimCard();
        $form = $this->createForm(SimCardType::class, $simCard);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $simCard->setAuthor($user);
            $this->manager->persist($simCard);
            $this->manager->flush();
            $this->addFlash('success', "Le numéro <strong>{$simCard->getMsisdn()}</strong> a bien été ajouter");
            return $this->redirectToRoute('sim_card');
        }
        return  $this->render('sim_card/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param SimCard $simCard
     * @return Response
     * @Route("/simcards/{id}/edit", name="sim_card_edit")
     */
    public function edit(Request $request, SimCard $simCard):Response
    {
        $user = $this->getUser();
        $form = $this->createForm(SimCardType::class, $simCard);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $simCard->setAuthor($user);
            $this->manager->persist($simCard);
            $this->manager->flush();
            $this->addFlash('success', "Le numéro <strong>{$simCard->getMsisdn()}</strong> a bien été modifier");
            return $this->redirectToRoute('sim_card');
        }
        return  $this->render('sim_card/form.html.twig', [
            'form' => $form->createView(),
            'simCard' => $simCard,
        ]);
    }

    /**
     * @param SimCard $simCard
     * @return Response
     * @Route("/simcards/{id}/delete", name="sim_card_delete")
     */
    public function delete(SimCard $simCard):Response
    {
        $this->addFlash('success', "Le numéro <strong>{$simCard->getMsisdn()}</strong> a bien été supprimer");
        $this->manager->remove($simCard);
        $this->manager->flush();
        return $this->redirectToRoute('sim_card');
    }

    /**
     * @param ProfileRepository $pRepo
     * @return Response
     * @Route("/simcards/i", name="sim_card_inactive")
     */
    public function update(ProfileRepository $pRepo):Response
    {

        $date = $this->repository->findPointofsaleLastUpdate();

        $profile = $pRepo->findOneBy(['title'=>'AGNT']);
        $this->repository->setInactive($profile, $date);

        $profile = $pRepo->findOneBy(['title'=>'DISTRO']);
        $this->repository->setInactive($profile, $date);

        return $this->redirectToRoute('sim_card');
    }
}
