<?php

namespace App\Controller;

use App\Entity\SocialMedia;
use App\Entity\User;
use App\Form\SocialMediaType;
use App\Repository\SocialMediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/social/media")
 */
class SocialMediaController extends AbstractController
{
//    /**
//     * @Route("/", name="social_media_index", methods={"GET"})
//     */
//    public function index(SocialMediaRepository $socialMediaRepository): Response
//    {
//        return $this->render('social_media/signals.html.twig', [
//            'social_media' => $socialMediaRepository->findAll(),
//        ]);
//    }

    /**
     * @Route("/new", name="social_media_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $socialMedia = new SocialMedia();
        $socialMedia->setUser($this->getUser());
        $form = $this->createForm(SocialMediaType::class, $socialMedia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($socialMedia);
            $entityManager->flush();
            $this->addFlash('success','Social Link Media Added !');
            return new Response("success");
        }

        return $this->render('social_media/new.html.twig', [
            'social_media' => $socialMedia,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="social_media_show", methods={"GET"})
//     */
//    public function show(SocialMedia $socialMedia): Response
//    {
//        return $this->render('social_media/show.html.twig', [
//            'social_media' => $socialMedia,
//        ]);
//    }

    /**
     * @Route("/edit/{id}", name="social_media_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,SocialMedia $socialMedia): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $form = $this->createForm(SocialMediaType::class, $socialMedia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Social Media Link Edited !');
            return new Response("success");
        }

        return $this->render('social_media/edit.html.twig', [
            'social_media' => $socialMedia,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="social_media_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SocialMedia $socialMedia): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        if ($this->isCsrfTokenValid('delete'.$socialMedia->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($socialMedia);
            $entityManager->flush();
            $this->addFlash('success','Social Media Link Deleted !');
        }

        return $this->redirectToRoute('social_media_index');
    }
}
