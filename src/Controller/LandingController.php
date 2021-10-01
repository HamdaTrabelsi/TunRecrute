<?php


namespace App\Controller;
use App\Repository\AnnonceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LandingController extends AbstractController
{
    /**
     * @Route("/", name="app_index", methods={"GET"})
     */
    public function index(AnnonceRepository $annonceRepository,UserRepository $userRepository, Request $request):Response
    {
        if($this->isGranted("ROLE_CANDIDAT") || $this->isGranted("ROLE_EMPLOYER")){
            return $this->redirectToRoute('annonce_index');
        }
        $numA = $annonceRepository->CountAll();
        $numU = $userRepository->CountAll();
        $annonces = $annonceRepository->GetSample();
        return $this->render('landing/index.html.twig',[
            'numA' => $numA,
            'numU' => $numU,
            'annonces' => $annonces,
        ]);
    }
}