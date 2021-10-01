<?php


namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EditPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('password',RepeatedType::class,[
                'type' => PasswordType::class,
                'required' => false,
                'invalid_message' => 'The password field must match.',
                'first_options'  => ['label' => false],
                'second_options' => ['label' => false],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

            //'role' => 'ROLE_USER'
        ]);
    }
    public function getBlockPrefix()
    {
        return 'appbundle_edit_password';
    }
}