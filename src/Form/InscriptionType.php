<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'required'=>false,
                'label'=>'Pseudo',
                'attr'=>[
                    'placeholder'=>'Saisissez votre pseudo'
                ]
            ])
            ->add('email', EmailType::class,[
                'required'=>false,
                'label'=>'Email',
                'attr'=>[
                    'placeholder'=>'Saisissez votre email'
                ]
            ])
            ->add('password', PasswordType::class,[
                'required'=>false,
                'label'=>'Mot de passe',
                'attr'=>[
                    'placeholder'=>'Saisissez votre mot de passe'
                ]
            ])
            ->add('confirmPassword', PasswordType::class,[
                'required'=>false,
                'constraints'=>[
                    new Length(['min'=>4, 'minMessage'=>'Votre mot de passe doit contenir un minimum de 4 caractères'])
                ],
                'label'=>'Confirmation de mot de passe',
                'attr'=>[
                    'placeholder'=>'Confirmez votre mot de passe'
                ]
            ])
            ->add("Enregistrer", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
