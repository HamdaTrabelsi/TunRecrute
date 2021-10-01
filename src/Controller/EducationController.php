<?php

namespace App\Controller;

use App\Entity\Education;
use App\Form\EducationType;
use App\Repository\EducationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/education")
 */
class EducationController extends AbstractController
{
//    /**
//     * @Route("/", name="education_index", methods={"GET"})
//     */
//    public function index(EducationRepository $educationRepository): Response
//    {
//        return $this->render('education/signals.html.twig', [
//            'education' => $educationRepository->findAll(),
//        ]);
//    }

    /**
     * @Route("/new", name="education_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $education = new Education();
        $education->setUser($this->getUser());
        $form = $this->createForm(EducationType::class, $education);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($education);
            $entityManager->flush();
            $this->addFlash('success','Education experience added !');
            return new Response('success');
        }
        return $this->render('education/new.html.twig', [
            'education' => $education,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="education_show", methods={"GET"})
//     */
//    public function show(Education $education): Response
//    {
//        return $this->render('education/show.html.twig', [
//            'education' => $education,
//        ]);
//    }

    /**
     * @Route("/edit/{id}", name="education_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,Education $education): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $form = $this->createForm(EducationType::class, $education);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Education experience info edited !');
            return new Response('success');
            //return $this->redirectToRoute('resume_edit');
        }
        return $this->render('education/edit.html.twig', [
            'education' => $education,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="education_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Education $education): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        if ($this->isCsrfTokenValid('delete'.$education->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($education);
            $entityManager->flush();
            $this->addFlash('success','Education experience deleted !');
            return $this->redirectToRoute('resume_edit');
        }

        return $this->render('education/_delete_form.html.twig',[
            'education'=>$education
        ]);
    }
}
