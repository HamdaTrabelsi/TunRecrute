<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Signal;
use App\Form\AnnonceType;
use App\Form\ReportType;
use App\Repository\AnnonceRepository;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/annonce")
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("/", name="annonce_index", methods={"GET"})
     */
    public function index(AnnonceRepository $annonceRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $user=$this->getUser();

        $annonces = $paginator->paginate(
            $annonceRepository->findallannonce(),
            $request->query->getInt('page',1),
            10
        );
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
            'user' =>$user,
        ]);
    }
    /**
     * @Route("/mes_annonces",name="mes_annonces")
     */
    public function mesAnnonces(AuthorizationCheckerInterface $authChecker,Request $request,PaginatorInterface $paginator){
        if (!$authChecker->isGranted('ROLE_EMPLOYER')){
            return $this->redirectToRoute('app_index');
        }
        $annonces=$paginator->paginate(
            $this->getUser()->getAnnonces(),
            $request->query->getInt('page',1),
            5
        );
        return $this->render('annonce/mesannonces.html.twig', [
            'annonces' => $annonces,
        ]);
    }


    /**
     * @Route("/new", name="annonce_new", methods={"GET","POST"})
     */
    public function new(Request $request,AuthorizationCheckerInterface $authChecker): Response
    {
        if (!$authChecker->isGranted('ROLE_EMPLOYER')){
            return $this->redirectToRoute('annonce_index');
        }
        $annonce = new Annonce();
        $annonce->setUser($this->getUser());
        $annonce->setPosted(new \DateTime());
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($annonce);
            $entityManager->flush();
            $this->addFlash('success','Job Created!');
            return $this->redirectToRoute('annonce_index');
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="annonce_show", methods={"GET","POST"},requirements={"id"="\d+"})
     */
    public function show(Request $request,AuthorizationCheckerInterface $authChecker,Annonce $annonce,$id,AnnonceRepository$an): Response
    {
        $report = new Signal();
        $report->setUserId($this->getUser());
        $report->setTime(new \DateTime());
        $report->setAnnonceId($an->findOneBy(array('id' => $id)));
//        $report->setDescription("test");
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($report);
            $entityManager->flush();
            $this->addFlash('success','Job Reported');

            return $this->redirectToRoute('annonce_index');
        }
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="annonce_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonce $annonce,AuthorizationCheckerInterface $authChecker): Response
    {
        if (!$authChecker->isGranted('ROLE_EMPLOYER') || $this->getUser()->getId()!=$annonce->getUser()->getId()){
            return $this->redirectToRoute('annonce_index');
        }
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Job Updated');
            return $this->redirectToRoute('annonce_edit',['id'=>$annonce->getId()]);
        }

        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="annonce_delete")
     */
    public function delete(Request $request, Annonce $annonce,AuthorizationCheckerInterface $authChecker,AnnonceRepository $an): Response
    {
        if ($authChecker->isGranted('ROLE_CANDIDAT') && $this->getUser()->getId()!=$annonce->getUser()->getId()){
            return $this->redirectToRoute('annonce_index');
        }
        if ($annonce) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
            if($authChecker->isGranted("ROLE_ADMIN")){
                $this->addFlash('success','Job Deleted');
                return $this->redirectToRoute('admin_dash');

            }
            $this->addFlash('success','Job Deleted');
            return $this->redirectToRoute('mes_annonces');
        }else{
            throw new InvalidArgumentException();
        }

    }
    /**
     * @Route("/report/{id}", name="annonce_report", methods={"GET","POST"})
     */
    public function Report(Request $request, Annonce $annonce,AuthorizationCheckerInterface $authChecker,AnnonceRepository $an,$id): Response
    {
        $report = new Signal();
        $report->setUserId($this->getUser());
        $report->setTime(new \DateTime());
        $report->setAnnonceId($an->findOneBy(array('id' => $id)));
        $report->setDescription("test");
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($report);
        $entityManager->flush();
        $this->addFlash('success','Job Reported');

        return $this->redirectToRoute('annonce_index');

    }

    /**
     * @Route("/search", name="search_annonce")
     */
    public function findAnnonce(Request $request,AnnonceRepository $an, PaginatorInterface $paginator):Response
    {
        $annonces=[];
        $titre="";
        $domaine="";
        $location="";
        $diploma ="";
        $contract = "";
        $exp = "";
        $etat = false;
        $searched = false;
        if($request->getMethod()=="POST"){
            $titre=$request->request->get('titre');
            $domaine=$request->request->get('domaine');
            $location = $request->request->get('address');
            $diploma = $request->request->get('diploma');
            $contract = $request->request->get('contract');
            $exp = $request->request->get('experience');

            $annonces = $paginator->paginate(
                $an->SearchByfields($titre,$domaine,$location,$contract,$diploma,$exp),
                $request->query->getInt('page',1),
                10
            );
            $searched = true;
            $etat=true;
        }
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
            'titre' =>$titre,
            'etat' =>$etat,
            'searched' => $searched
        ]);
    }


}
