<?php

namespace App\Controller;

use App\Entity\LanguageProf;
use App\Form\LanguageProfType;
use App\Repository\LanguageProfRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/language/prof")
 */
class LanguageProfController extends AbstractController
{
//    /**
//     * @Route("/", name="language_prof_index", methods={"GET"})
//     */
//    public function index(LanguageProfRepository $languageProfRepository): Response
//    {
//        return $this->render('language_prof/signals.html.twig', [
//            'language_profs' => $languageProfRepository->findAll(),
//        ]);
//    }

    /**
     * @Route("/new", name="language_prof_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $languageProf = new LanguageProf();
        $languageProf->setUser($this->getUser());
        $form = $this->createForm(LanguageProfType::class, $languageProf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($languageProf);
            $entityManager->flush();
            $this->addFlash('success','Language added !');
            return new Response("success");

        }

        return $this->render('language_prof/new.html.twig', [
            'language_prof' => $languageProf,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="language_prof_show", methods={"GET"})
//     */
//    public function show(LanguageProf $languageProf): Response
//    {
//        return $this->render('language_prof/show.html.twig', [
//            'language_prof' => $languageProf,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="language_prof_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LanguageProf $languageProf): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $form = $this->createForm(LanguageProfType::class, $languageProf);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Language edited !');
            return new Response("success");
        }

        return $this->render('language_prof/edit.html.twig', [
            'language_prof' => $languageProf,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="language_prof_delete", methods={"DELETE"})
     */
    public function delete(Request $request, LanguageProf $languageProf): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        if ($this->isCsrfTokenValid('delete'.$languageProf->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($languageProf);
            $entityManager->flush();
            $this->addFlash('success','Language deleted !');
            return $this->redirectToRoute('resume_edit');
        }

        return $this->render('language_prof/_delete_form.html.twig',[
            'language_prof'=>$languageProf
        ]);
    }
}
