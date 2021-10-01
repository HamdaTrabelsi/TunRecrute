<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils,UserRepository $ur): Response
    {
        if ($this->getUser()) {
            $this->addFlash('success','Welcome To TunRecrute');

             return $this->redirectToRoute('annonce_index');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        if($error) {
            if ($error instanceof DisabledException) {
                $this->addFlash('warning', 'Account is Deactivated');

            } else {
//                $this->addFlash('error', 'Wrong Password');
                $this->addFlash('error', $error->getMessage());

            }
        }
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername]);

//        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'wrongpass' => $error]);
    }
    /**
     * @Route("/reactivate",name="reactivate")
     */
    public function reactivate(){

    }
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
