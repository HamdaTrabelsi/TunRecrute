<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Form\CandidatureType;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatureRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/candidature")
 */
class CandidatureController extends AbstractController
{
    /**
     * @Route("/applications", name="candidature_index", methods={"GET"})
     */
    public function index(CandidatureRepository $candidatureRepository,AuthorizationCheckerInterface $authChecker,PaginatorInterface $paginator,Request $request): Response
    {
        if (!$authChecker->isGranted('ROLE_CANDIDAT') && !$authChecker->isGranted('ROLE_EMPLOYER')){
            return $this->redirectToRoute('app_index');
        }
        $userId = $this->getUser()->getId();
        //si l'utilisateur connecté est un candidat
        if($authChecker->isGranted('ROLE_CANDIDAT'))  {
            $candidatures=$paginator->paginate(
                $candidatureRepository->findBy(array("User"=>$userId)),
                $request->query->getInt('page',1),
                5
            );
        }else{
            $candidatures=$paginator->paginate(
                $candidatures=$candidatureRepository->findByExampleField($userId),
                $request->query->getInt('page',1),
                5
            );
        }
        return $this->render('Profile/employer/candidates.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }
    /**
     * @Route("/managed/applications",name="managed_applications",methods={"GET"})
     */
    public function managedApplications(CandidatureRepository $candidatureRepository,AuthorizationCheckerInterface $authChecker,PaginatorInterface $paginator,Request $request):Response
    {
        if (!$authChecker->isGranted('ROLE_CANDIDAT') && !$authChecker->isGranted('ROLE_EMPLOYER')){
            return $this->redirectToRoute('app_index');
        }
        $userId = $this->getUser()->getId();
        if($authChecker->isGranted('ROLE_EMPLOYER'))  {
            $candidatures=$paginator->paginate(
                $candidatureRepository->findPastApps($userId),
                $request->query->getInt('page',1),
                5
            );
        }
        return $this->render('Profile/employer/managedApps.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }
    /**
     * @Route("/{annonce_id}/{user_id}", name="candidature_par_annonce", methods={"GET"})
     */
    public function CandiatureParAnnonce(CandidatureRepository $candidatureRepository,AuthorizationCheckerInterface $authChecker,int $annonce_id,int $user_id, PaginatorInterface $paginator,Request $request): Response
    {
        if (!$authChecker->isGranted('ROLE_EMPLOYER') && $this->getUser()->getId()!=$user_id){
            return $this->redirectToRoute('annonce_index');
        }
        $candidatures = $paginator->paginate(
            $candidatureRepository->findBy(array("Annonce"=>$annonce_id)),
            $request->query->getInt('page',1),
            5
        );
        return $this->render('candidature/index.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }


    /**
     * @Route("/new/{annonceId}/{UserId}", name="candidature_new", methods={"GET","POST"})
     */
    public function new(Request $request,AuthorizationCheckerInterface $authChecker,int $UserId,int $annonceId,AnnonceRepository $ar): Response
    {
        if (!$authChecker->isGranted('ROLE_CANDIDAT') && $this->getUser()->getId() == $UserId){
            return $this->redirectToRoute('candidature_index');
        }
        $candidature = new Candidature();
        $candidature->setUser($this->getUser());
        $annonce = $ar->find($annonceId);
        $candidature->setAnnonce($annonce);
        $candidature->setDate(new \DateTime());
        $candidature->setIsDeleted(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($candidature);
        $entityManager->flush();
        $this->addFlash('success','Your application has been sent');
        return $this->redirectToRoute('annonce_show',[
            'id' => $annonceId]);
    }

    /**
     * @Route("/{User}", name="candidature_show", methods={"GET"})
     */
    public function show(Candidature $candidature,AuthorizationCheckerInterface $authChecker): Response
    {
        if (!$authChecker->isGranted('ROLE_CANDIDAT') ){
            return $this->redirectToRoute('candidature_index');
        }
        return $this->render('candidature/show.html.twig', [
            'candidature' => $candidature,
        ]);
    }

    /**
     * @Route("/{User}/edit", name="candidature_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Candidature $candidature,AuthorizationCheckerInterface $authChecker): Response
    {
        if (!$authChecker->isGranted('ROLE_CANDIDAT') || $this->getUser()->getId()!=$candidature->getUser()->getId()){
            return $this->redirectToRoute('candidature_index');
        }
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('candidature_index');
        }

        return $this->render('candidature/edit.html.twig', [
            'candidature' => $candidature,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/status/{AnnonceId}/{UserId}/{s}",name="status",defaults={"s":null})
     */
    public function status(CandidatureRepository $cr,int $AnnonceId,int $UserId,?string $s,AuthorizationCheckerInterface $authChecker,Request $request){
        if (!$authChecker->isGranted('ROLE_EMPLOYER') && $this->getUser()->getId()!=$UserId){
            return $this->redirectToRoute('annonce_index');
        }
        $candidature = $cr->findOneBy(array("Annonce"=>$AnnonceId,"User"=>$UserId));
        $candidature->setStatus($s);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success','Applicant '.$s);
        $referer = $request->headers->get("referer");
        if($referer == "http://localhost:8000/candidature/applications") return $this->redirectToRoute('candidature_index');
        return $this->redirectToRoute('candidature_par_annonce',[
            'annonce_id'=>$AnnonceId,
            'user_id' => $UserId
        ]);
    }
    /**
     * @Route("delete/{UserId}/{AnnonceId}", name="candidature_delete")
     */
    public function delete($UserId,$AnnonceId,UserRepository $ur,AnnonceRepository $ar,Request $request, AuthorizationCheckerInterface $authChecker,CandidatureRepository $cr): Response
    {
        $user = $ur->find($UserId);
        $annonce = $ar->find($AnnonceId);
        $candidature = $cr->findOneBy(array('User'=>$user,'Annonce'=>$annonce));
        if (!$authChecker->isGranted('ROLE_CANDIDAT') || $this->getUser()->getId()!=$candidature->getUser()->getId()){
            return $this->redirectToRoute('annonce_index');
        }
        if ($candidature) {
            //dump($annonce);
            $candidature->setIsDeleted(true);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('applied_jobs');
    }
}
