<?php

namespace App\Form;

use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextareaType::class,
                [
                    'label' => 'Pseudo :',
                ])
            ->add('nom', TextareaType::class,
                [
                    'label' => 'Nom :',
                ])
            ->add('prenom', TextareaType::class,
                [
                    'label' => 'Prénom :',
                ])
            ->add('mail', EmailType::class,
                [
                    'label' => 'E-mail :',
                ])
            ->add('telephone', TextareaType::class,
                [
                    'label' => 'Téléphone :',
                ])
            ->add('site', EntityType::class, [
                'label' => 'Site :',
                'class' => Site::class,
                'choice_label' => 'nom'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passes doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ])
//            ->add('photo', FileType::class, [
//                'label' => 'Photo Image file (jpg, jpeg, png, gif)',
//                'mapped' => false,
//                'required' => false,
//                'constraints' => [
//                    new File([
//                        'maxSize' => '4096k',
//                        'mimeTypes' => [
//                            'image/jpeg',
//                            'image/jpg',
//                            'image/png',
//                            'image/gif',
//                            // jpg, jpeg, png, gif
//                        ],
//                        'mimeTypesMessage' => 'Please upload a valid vignette Media file',
//                    ])
//                ],
//            ])
            ->add('Enregistrer', SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
