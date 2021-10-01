<?php

namespace App\Controller;

use App\Entity\ProfSkill;
use App\Form\ProfSkillType;
use App\Repository\ProfSkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prof/skill")
 */
class ProfSkillController extends AbstractController
{
//    /**
//     * @Route("/", name="prof_skill_index", methods={"GET"})
//     */
//    public function index(ProfSkillRepository $profSkillRepository): Response
//    {
//        return $this->render('prof_skill/signals.html.twig', [
//            'prof_skills' => $profSkillRepository->findAll(),
//        ]);
//    }

    /**
     * @Route("/new", name="prof_skill_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $profSkill = new ProfSkill();
        $profSkill->setUser($this->getUser());
        $form = $this->createForm(ProfSkillType::class, $profSkill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profSkill);
            $entityManager->flush();
            $this->addFlash('success','Professional Skill Added !');
            return new Response("success");
        }

        return $this->render('prof_skill/new.html.twig', [
            'prof_skill' => $profSkill,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="prof_skill_show", methods={"GET"})
//     */
//    public function show(ProfSkill $profSkill): Response
//    {
//        return $this->render('prof_skill/show.html.twig', [
//            'prof_skill' => $profSkill,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="prof_skill_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProfSkill $profSkill): Response
    {
        $form = $this->createForm(ProfSkillType::class, $profSkill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Professional Skill Edited !');
            return new Response("success");
        }

        return $this->render('prof_skill/edit.html.twig', [
            'prof_skill' => $profSkill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prof_skill_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProfSkill $profSkill): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profSkill->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($profSkill);
            $entityManager->flush();
            $this->addFlash('success','Professional Skill Deleted !');
            return $this->redirectToRoute('resume_edit');
        }

        return $this->render('prof_skill/_delete_form.html.twig',[
            'prof_skill'=>$profSkill
        ]);
    }
}
