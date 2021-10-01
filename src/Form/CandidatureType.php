<?php

namespace App\Form;

use App\Entity\Candidature;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Date')
            ->add('Annonce',EntityType::class,array(
                'class'=>'App\Entity\Annonce',
                'label'=>'Annonce concerné',
                'choice_label'=>'id'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}
