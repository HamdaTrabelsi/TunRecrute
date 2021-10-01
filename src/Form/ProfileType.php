<?php

namespace App\Form;

use App\Entity\SocialMedia;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if(in_array('ROLE_CANDIDAT',$options['role'])){
            $builder
                ->add('email')
                ->add('firstname')
                ->add('lastname')
                ->add('address')
                ->add('tel')
                ->add('pictureFile',FileType::class,[
                    'mapped'=>false,
                    'required'=>false
                ]);

        }else if (in_array('ROLE_EMPLOYER',$options['role'])){
            $builder
                ->add('email')
                ->add('address')
                ->add('tel')
                ->add('compname')
                ->add('aboutme')
                ->add('pictureFile',FileType::class,[
                    'mapped'=>false,
                    'required'=>false,
                    'constraints'=>[
                        new Image()
                    ]
                ])
            ->add('socialMedia',SocialMediaType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'role' => 'ROLE_USER'
        ]);
    }
    public function getBlockPrefix()
    {
        return 'appbundle_general_profile';
    }
}
