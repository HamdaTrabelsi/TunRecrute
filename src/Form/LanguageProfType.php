<?php

namespace App\Form;

use App\Entity\LanguageProf;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageProfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('LanguageName')
            ->add('ProfeciencyLvl',ChoiceType::class,[
                'choices'=>[
                    'Native'=>'Native',
                    'Advanced'=>'Advanced',
                    'Intermediate'=>'Intermediate',
                    'Novice'=>'Novice',
                ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LanguageProf::class,
        ]);
    }
}
