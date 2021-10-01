<?php

namespace App\Controller;

use App\Entity\WorkExp;
use App\Form\WorkExpType;
use App\Repository\WorkExpRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/work/exp")
 */
class WorkExpController extends AbstractController
{
//    /**
//     * @Route("/", name="work_exp_index", methods={"GET"})
//     */
//    public function index(WorkExpRepository $workExpRepository): Response
//    {
//        return $this->render('work_exp/signals.html.twig', [
//            'work_exps' => $workExpRepository->findAll(),
//        ]);
//    }

    /**
     * @Route("/new", name="work_exp_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $workExp = new WorkExp();
        $workExp->setUser($this->getUser());
        $form = $this->createForm(WorkExpType::class, $workExp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($workExp);
            $entityManager->flush();
            $this->addFlash('success','Work Experience Added!');
            return new Response("success");
        }

        return $this->render('work_exp/new.html.twig', [
            'work_exp' => $workExp,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="work_exp_show", methods={"GET"})
//     */
//    public function show(WorkExp $workExp): Response
//    {
//        return $this->render('work_exp/show.html.twig', [
//            'work_exp' => $workExp,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="work_exp_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, WorkExp $workExp): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $form = $this->createForm(WorkExpType::class, $workExp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Work experience edited!');
            return new Response("success");
        }

        return $this->render('work_exp/edit.html.twig', [
            'work_exp' => $workExp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="work_exp_delete", methods={"DELETE"})
     */
    public function delete(Request $request, WorkExp $workExp): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        if ($this->isCsrfTokenValid('delete'.$workExp->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($workExp);
            $entityManager->flush();
            $this->addFlash('success','Work Experience Deleted!');
            return $this->redirectToRoute('resume_edit');
        }

        return $this->render('work_exp/_delete_form.html.twig',[
            'work_exp'=>$workExp
        ]);
    }
}
