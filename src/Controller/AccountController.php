<?php

namespace App\Controller;

use App\Entity\ChangePassword;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils):Response
    {
        $errors  = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('account/login.html.twig', [
            'hasError' => $errors !== null,
            'username' => $username,
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout():void
    {

    }

    /**
     * @Route("/account/password", name="account_password")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function changePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder):Response
    {
        $passwordUpdate = new ChangePassword();
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $passwordUpdate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez taper n'est pas votre mot de passe actuel !"));

            } else {

                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifier."
                );
                return $this->redirectToRoute('user');
            }
        }
        return $this->render('account/passwordUpdate.html.twig', [
            'form' => $form->createView(),
        ]) ;
    }

}
