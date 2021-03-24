<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('username', TextType::class,
                [
                    'label' => 'Pseudo :',

                ])
            ->add('nom', TextType::class,
                [
                    'label' => 'Nom :',

                ])
            ->add('prenom', TextType::class,
                [
                    'label' => 'Prénom :',

                ])
            ->add('mail', EmailType::class,
                [
                    'label' => 'E-mail :',

                ])
            ->add('telephone', TextType::class,
                [
                    'label' => 'Téléphone :',

                ])
            ->add('site', EntityType::class, [
                'label' => 'Site :',
                'disabled' => 'true',
                'class' => Site::class,
                'choice_label' => 'nom'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'mapped'=>true,
                'invalid_message' => 'Les deux mots de passes doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],

            ])

            ->add('Enregistrer', SubmitType::class, [
                'attr' => ['style' => 'background-color : #00487f; border-color : #00487f; color : white;']
            ]);}



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            // Configure your form options here
        ]);
    }
}
