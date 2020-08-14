<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\AccountEditType;
use App\Form\AccountType;
use App\Form\RoleType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user")
     * @param UserRepository $repository
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UserRepository $repository):Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/users/add", name="user_add")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(AccountType::class, $user);
        $role = new Role();
        $roleForm = $this->createForm(RoleType::class, $role);
        $roleForm->handleRequest($request);
        $form->handleRequest($request);
        if($roleForm->isSubmitted() && $roleForm->isValid()){
            $manager->persist($role);
            $manager->flush();
            return $this->redirectToRoute('user_add');
        }
        if($form->isSubmitted() && $form->isValid()){
            $password = $user->getPassword();
            $password = $encoder->encodePassword($user, $password);
            $user->setPassword($password);
            $this->addFlash('success', "L'utilisateur <strong>{$user->getFullName()}</strong> a bien été ajouter");
            $manager->persist($user);
            $manager->flush();
            return  $this->redirectToRoute('user');
        }


        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
            'roleForm' => $roleForm->createView(),
        ]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response.
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(User $user,Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AccountEditType::class, $user);
        $role = new Role();
        $roleForm = $this->createForm(RoleType::class, $role);
        $roleForm->handleRequest($request);
        $form->handleRequest($request);
        if($roleForm->isSubmitted() && $roleForm->isValid()){
            $manager->persist($role);
            $manager->flush();
            return $this->redirectToRoute('user_edit',['id'=>$user->getId()]);
        }
        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', "L'utilisateur <strong>{$user->getFullName()}</strong> a bien été modifier");
            $manager->persist($user);
            $manager->flush();
            return  $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'roleForm' => $roleForm->createView(),
            'user'=>$user
        ]);
    }

    /**
     * @Route("/users/{id}/delete", name="user_delete")
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(User $user, EntityManagerInterface $manager):Response
    {
        $this->addFlash('success', "L'utilisateur <strong>{$user->getFullName()}</strong> a bien été supprimer");
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('user');
    }
}
