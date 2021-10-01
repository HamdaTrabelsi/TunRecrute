<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description',TextareaType::class,[
                'attr' => ['style' => 'resize: none;height: 150px'],
            ])
            ->add('salaire',TextType::class)
            ->add('contrat',ChoiceType::class,[
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Full-Time' => 'Full-Time',
                    'Independent/Freelance' => 'Independent/Freelance',
                    'Internship' => 'Internship'
                ]
            ])
            ->add('experience',ChoiceType::class,[
                'choices' => [
                    'No Experience' => 'No Experience',
                    '1-2' => '1-2',
                    '3-4' => '3-4',
                    '5-6' => '5-6',
                    '7-8' => '7-8',
                    '9+' => '9+',
                ]
            ])
            ->add('adresseTravail',TextType::class)
            ->add('heuresTravail',TextType::class)
            ->add('diplome',ChoiceType::class,[
                'choices' => [
                    'No Diploma' => 'No Diploma',
                    'Bachelor' => 'Bachelor',
                    'Ph.D.' => 'Ph.D.',
                    'Master' => 'Master',
                ]
            ])
            ->add('domaine',ChoiceType::class,[
                'choices' => [
                    'Banking and Finance' => 'Banking and Finance',
                    'Education' => 'Education',
                    'Consulting' => 'Consulting',
                    'Marketing and Advertising' => 'Marketing and Advertising',
                    'Technology' => 'Technology',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
