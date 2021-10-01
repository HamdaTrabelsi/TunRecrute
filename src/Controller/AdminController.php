<?php

namespace App\Controller;


use App\Entity\Annonce;
use App\Entity\Signal;
use App\Entity\SocialMedia;
use App\Entity\User;
use App\Form\AdminType;
use App\Form\RegisterAdminType;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatureRepository;
use App\Repository\SignalRepository;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use http\Exception\InvalidArgumentException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="admin_dash", methods={"GET"})
     */
    public function index(UserRepository $userRepository,Request $request,PaginatorInterface $paginator,SignalRepository $signalRepository,AnnonceRepository $annonceRepository, CandidatureRepository $candidatureRepository): Response
    {
        $orderAnnonces = $this->getDoctrine()->getRepository('App:Annonce')
            ->createQueryBuilder('a');

        $annonces =$orderAnnonces->addselect('a, COUNT(signal.id) AS HIDDEN mycount')
            ->leftJoin('a.signals', 'signal')
            ->groupBy('signal.annonce_id')
            ->orderBy('mycount', 'DESC')
			->having('COUNT(signal.id) > 0')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
//
//        $annonces = $paginator->paginate(
//            $annonceRepository->findallannonce(),
//            $request->query->getInt('page',1),
//            1
//        );
        $nbbycomp = $userRepository->CountAnnonce();
        $nbdom = $annonceRepository->CountDomaine();
        $nbUser = $userRepository->CountAll();
        $nbSign = $signalRepository->CountAll();
        $nbAnn = $annonceRepository->CountAll();
        $nbCan = $candidatureRepository->CountAll();


        return $this->render('admin/index.html.twig', [
            'bycomp' =>$nbbycomp,
            'nbdom' => $nbdom,
            'annonces' => $annonces,
            'nbUser' => $nbUser,
            'nbSign' => $nbSign,
            'nbAnn' => $nbAnn,
            'nbCan' => $nbCan,

        ]);
    }


    /**
     * @Route("/signals", name="admin_signals", methods={"GET"})
     */
    public function AdminSignals(Request $request,PaginatorInterface $paginator,SignalRepository $signalRepository,AnnonceRepository $annonceRepository, UserRepository $userRepository, CandidatureRepository $candidatureRepository): Response
    {

        $orderSignals = $this->getDoctrine()->getRepository('App:Annonce')
            ->createQueryBuilder('a');

        $ann = $orderSignals->addselect('a, COUNT(signal.id) AS HIDDEN mycount')
            ->leftJoin('a.signals', 'signal')
            ->groupBy('signal.annonce_id')
            ->orderBy('mycount', 'DESC')
            ->having('COUNT(signal.id) > 0')
            ->getQuery()
            ->getResult();

        $nbUser = $userRepository->CountAll();
        $nbSign = $signalRepository->CountAll();
        $nbAnn = $annonceRepository->CountAll();
        $nbCan = $candidatureRepository->CountAll();

        $annonces = $paginator->paginate(
            $ann,
            $request->query->getInt('page',1),
            10
        );
        return $this->render('admin/signals.html.twig', [
            'annonces' => $annonces,
            'nbUser' => $nbUser,
            'nbSign' => $nbSign,
            'nbAnn' => $nbAnn,
            'nbCan' => $nbCan,

        ]);
    }


    /**
     * @Route("/signals/{annonce_id}", name="annonce_signals", methods={"GET"})
     */
    public function AnnonceSignals(int $annonce_id,Request $request,PaginatorInterface $paginator,SignalRepository $signalRepository,AnnonceRepository $annonceRepository, UserRepository $userRepository, CandidatureRepository $candidatureRepository): Response
    {
        $signals = $paginator->paginate(
            $signalRepository->findBy(array("annonce_id"=>$annonce_id)),
            $request->query->getInt('page',1),
            5
        );

        $nbUser = $userRepository->CountAll();
        $nbSign = $signalRepository->CountAll();
        $nbAnn = $annonceRepository->CountAll();
        $nbCan = $candidatureRepository->CountAll();

        return $this->render('admin/signalsByannonce.html.twig', [
            'signals' => $signals,
            'nbUser' => $nbUser,
            'nbSign' => $nbSign,
            'nbAnn' => $nbAnn,
            'nbCan' => $nbCan,

        ]);
    }

    /**
     * @Route("/users", name="admin_users", methods={"GET"})
     */
    public function ShowUsers(Request $request,PaginatorInterface $paginator,SignalRepository $signalRepository,AnnonceRepository $annonceRepository, UserRepository $userRepository, CandidatureRepository $candidatureRepository): Response
    {
        $nbUser = $userRepository->CountAll();
        $nbSign = $signalRepository->CountAll();
        $nbAnn = $annonceRepository->CountAll();
        $nbCan = $candidatureRepository->CountAll();
        $users = $paginator->paginate(
            $userRepository->findallusers(),
            $request->query->getInt('page',1),
            10
        );
        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'nbUser' => $nbUser,
            'nbSign' => $nbSign,
            'nbAnn' => $nbAnn,
            'nbCan' => $nbCan,

        ]);
    }
    /**
     * @Route("/signal/delete/{id}", name="signal_delete")
     */
    public function delete($id,Request $request,AuthorizationCheckerInterface $authChecker,SignalRepository $signalRepository): Response
    {
        $sign = $this->getDoctrine()
            ->getRepository(Signal::class)
            ->find($id);
        if ($sign) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sign);
            $entityManager->flush();
            $this->addFlash('success','Report Deleted');
            return $this->redirectToRoute('annonce_signals');
        }else{
            throw new InvalidArgumentException();
        }

    }
    /**
     * @Route("/deactivate/{id}", name="deactivate_user")
     */
    public function DeactivateUser($id,Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if ($user && $user->getIsActive()) {
            $user->setIsActive(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('success','User Profile Deactivated');
            return $this->redirectToRoute('admin_users');
        }else if($user && !$user->getIsActive()){
            $this->addFlash('success','User Profile Is Already Deactivated');
            return $this->redirectToRoute('admin_users');
        }else{
            throw new InvalidArgumentException();
        }

    }

    /**
     * @Route("/activate/{id}", name="activate_user")
     */
    public function ActivateUser($id,Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if ($user && !$user->getIsActive()) {
            $user->setIsActive(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('success','User Profile Activated');
            return $this->redirectToRoute('admin_users');
        }else if($user && $user->getIsActive()) {
            $this->addFlash('success','User Profile Is Already Active');
            return $this->redirectToRoute('admin_users');
        }else{
            throw new InvalidArgumentException();

        }

    }

    /**
     * @Route("/edit", name="admin_edit", methods={"GET","POST"})
     */
    public function editAdmin(Request $request,AuthorizationCheckerInterface $authChecker, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AdminType::class,$user);
        $form->handleRequest($request);

        $newUser = new User();
        $addform = $this->createForm(RegisterAdminType::class,$newUser);
        $addform->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Profile Edited');
            return $this->redirectToRoute('admin_edit');
        }

        if ($addform->isSubmitted() && $addform->isValid()) {
            // encode the plain password
            $newUser->setSocialMedia(new SocialMedia());
            $newUser->setRoles(['ROLE_ADMIN']);
            $newUser->setIsActive(true);
            $newUser->setPassword(
                $passwordEncoder->encodePassword(
                    $newUser,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newUser);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success','User Added');
            return $this->redirectToRoute('admin_edit');
        }

        return $this->render('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'addform' => $addform->createView(),

        ]);
    }


}
