<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\EditPasswordType;
use App\Form\ProfileType;
use App\Repository\CandidatureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\File;


class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/edit",name="edit_profile")
     */
    public function editProfile(Request $request,EntityManagerInterface $em,UserPasswordEncoderInterface $passwordEncoder,AuthorizationCheckerInterface $authChecker){
        $user=$this->getUser(); //utilisateur connecté
        if(!$user) return $this->redirectToRoute('app_login');
        $user_roles=$user->getRoles(); //role de l'utilisateur connecté
        //les champs du formulaire dependent du role.Voir src/Form/ProfileType.php
        $form = $this->createForm(ProfileType::class,$user,array('role'=>$user_roles));
        $form->handleRequest($request);
        $form2 = $this->createForm(EditPasswordType::class);
        $form2->handleRequest($request);
        if ($form2->isSubmitted() && $form2->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form2->get('password')->get('first')->getData()
                )
            );
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Password Updated!');
            return $this->redirectToRoute('edit_profile');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            //upload image
            /** @var UploadedFile $uploadedfile */
            $uploadedfile=$form['pictureFile']->getData();
            if($uploadedfile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/Uploads/User_Images';
                $originalFilename = pathinfo($uploadedfile->getClientOriginalName(), PATHINFO_FILENAME);//nom sans extension
                $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedfile->guessExtension();//uniformiser nom des images

                $uploadedfile->move(
                    $destination,
                    $newFilename
                );
                $user->setPicture($newFilename);
            }
            /** @var User $user */
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'User Updated!');
            return $this->redirectToRoute('edit_profile');
        }
        if ($authChecker->isGranted('ROLE_CANDIDAT'))
        return $this->render('Profile/candidate/edit.html.twig',[
            'profileForm'=>$form->createView(),
            'form'=>$form2->createView()
        ]);
        return $this->render('Profile/employer/edit.html.twig', [
            'profileForm'=>$form->createView(),
            'form'=>$form2->createView()
        ]);
    }

    /**
    * @Route("/profile/resume/edit",name="resume_edit")
    */
    public function resumeEdit(AuthorizationCheckerInterface $authChecker,UserRepository $ur,EntityManagerInterface $em,Request $request){
        if ($authChecker->isGranted('ROLE_CANDIDAT')) {
            $user = $ur->find($this->getUser()->getId());
            $form = $this->createFormBuilder($user)
                ->add('cv',FileType::class,[
                    'mapped'=>false,
                    'required'=>false,
                    'constraints'=> new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document'
                ])])
                ->add('coverLetter',FileType::class,[
                    'mapped'=>false,
                    'required'=>false,
                    'constraints'=> new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document'
                ])])
                ->getForm();
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                /** @var UploadedFile $cvFilename */
                $cvFilename = $form->get('cv')->getData();
                /** @var UploadedFile $coverLetterFilename */
                $coverLetterFilename = $form->get('coverLetter')->getData();
                if($cvFilename){
                    $originalFilename = pathinfo($cvFilename->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$cvFilename->guessExtension();
                    $cvFilename->move(
                        $this->getParameter('pdf_files_directory'),
                        $newFilename
                    );
                    $user->setCvFilename($newFilename);
                }
                if($coverLetterFilename){
                    $originalFilename = pathinfo($coverLetterFilename->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$coverLetterFilename->guessExtension();
                    $coverLetterFilename->move(
                        $this->getParameter('pdf_files_directory'),
                        $newFilename
                    );
                    $user->setCoverLetterFilename($newFilename);
                }
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'File Uploaded!');
                return $this->redirectToRoute('resume_edit');

            }
            return $this->render("profile/candidate/editResume.html.twig",[
                'form_upload'=>$form->createView()
            ]);
        }
        return $this->redirectToRoute('app_login');

    }
    /**
     * @Route("/profile/resume/{id}",name="resume",defaults={"id"=null})
     */
    public function resume(AuthorizationCheckerInterface $authChecker,?int $id,UserRepository $ur){

        if ($authChecker->isGranted('ROLE_EMPLOYER') && $id) {
            $user = $ur->find($id);
            return $this->render("profile/employer/resume.html.twig", [
                'user' => $user
            ]);
        }else if($authChecker->isGranted('ROLE_CANDIDAT'))
            return $this->render("profile/candidate/resume.html.twig");
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/profile/applied",name="applied_jobs")
     */
    public function applied(CandidatureRepository $candidatureRepository,AuthorizationCheckerInterface $authChecker){
        if ($authChecker->isGranted('ROLE_CANDIDAT')){
            $candidatures=$candidatureRepository->findBy(['User'=>$this->getUser()]);
            return $this->render("profile/candidate/applied.html.twig", [
                'candidatures'=>$candidatures,
                'count'=>count($candidatures)
            ]);
        }
        return $this->redirectToRoute('app_login');

    }
    /**
     * @Route("/user/about",name="about_user")
     */
    public function about_user(Request $request){
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $user=$this->getUser();
        $form=$this->createFormBuilder($user)
            ->add('aboutme')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('resume_edit');
        }
        return $this->render('/profile/candidate/aboutme.html.twig',[
            'form'=>$form->createView(),
            'var'=>$var
        ]);
    }
    /**
     * @Route("/user/details",name="details")
     */
    public function user_details(Request $request){
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $user=$this->getUser();
        $form=$this->createFormBuilder($user)
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('birthdate',TextType::class)
            ->add('nationality')
            ->add('sex',ChoiceType::class,[
                'choices'=>[
                    'Male'=>'Male',
                    'Female'=>'Female',
                ]
            ])
            ->add('address')
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success','Personal details edited !');
            return new Response("success");
        }
        return $this->render('/profile/candidate/personaldetails.html.twig',[
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/downloadcv",name="downloadCV")
     */
    public function downCV(UserRepository $ur,Request $request){
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $user = $ur->find($this->getUser()->getId());
        $cvFilename = $user->getCvFilename();
        $dir = $this->getParameter('pdf_files_directory');
        $response = new BinaryFileResponse($dir.$cvFilename);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,$cvFilename);
        return $response;
    }
    /**
     * @Route("/downloadcl",name="downloadCL")
     */
    public function downCL(UserRepository $ur,Request $request){
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $user = $ur->find($this->getUser()->getId());
        $clFilename = $user->getCoverLetterFilename();
        $dir = $this->getParameter('pdf_files_directory');
        $response = new BinaryFileResponse($dir.$clFilename);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,$clFilename);
        return $response;
    }
    /**
     * @Route("/deactivate/{id}",name="deactivate")
     */
    public function deactivate(UserRepository $ur,$id,Request $request){
        $var = $request->headers->get("sec-fetch-site");
        if($var == "none" ) return $this->redirectToRoute("annonce_index");
        $user = $ur->find($id);
        $form=$this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setIsActive(false);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('app_logout');
        }
        return $this->render('profile/deactivate.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     *
     */
}