<?php

namespace App\Form;

use App\Entity\SocialMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('facebook',TextType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('twitter',TextType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('google',TextType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('linkedin',TextType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('pintrest',TextType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('instagram',TextType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('behance',TextType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('dribbble',TextType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('github',TextType::class,[
                'label'=>false,
                'required'=>false
            ])
            ->add('youtube',TextType::class,[
                'label'=>false,
                'required'=>false
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SocialMedia::class,
        ]);
    }
}
