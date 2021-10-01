<?php

namespace App\Controller;

use App\Entity\SocialMedia;
use App\Entity\User;
use App\Form\RegisterAdminType;
use App\Form\RegisterCandidatFormType;
use App\Form\RegisterEmployerFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registerCand", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterCandidatFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setSocialMedia(new SocialMedia());
            // encode the plain password
            $user->setRoles(['ROLE_CANDIDAT']);
            $user->setIsActive(true);
            $user->setPicture('profile.png');
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/registerCandidat.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registerEmp", name="employer_reg")
     */
    public function registerEmployer(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterEmployerFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setSocialMedia(new SocialMedia());
            $user->setIsActive(true);
            // encode the plain password
            $user->setRoles(['ROLE_EMPLOYER']);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/RegisterEmployer.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/registeradmin", name="admin_register")
     */
    public function registerAdmin(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterAdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setSocialMedia(new SocialMedia());
            $user->setRoles(['ROLE_ADMIN']);
            $user->setIsActive(true);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/RegisterAdmin.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/chooseRole",name="Choose_Role")
     */
    public function choose()
    {
        return $this->render('registration/ChooseRole.html.twig');
    }
}
