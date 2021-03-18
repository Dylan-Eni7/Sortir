<?php

namespace App\Form;

use App\Entity\Site;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                [
                    'label' => 'Pseudo :',
                ])
            ->add('nom', \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                [
                    'label' => 'Nom :',
                ])
            ->add('prenom', \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                [
                    'label' => 'Prénom :',
                ])
            ->add('mail', \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                [
                    'label' => 'E-mail :',
                ])
            ->add('telephone', \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                [
                    'label' => 'Téléphone :',
                ])
            ->add('site', EntityType::class, [
                'label' => 'Campus :',
                // looks for choices from this entity
                'class' => Site::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'nom',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe :',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'Merci d\'entrer un mot de passe',
                        ]
                    ),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères!',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                        'maxMessage' => 'Votre mot de passe doit contenir au maximum {{ limit }} caractères!',
                    ]),
                ],
            ])
            ->add(
                'newPassword',
                PasswordType::class,
                [

                    'invalid_message' => 'Les mots de passe ne correspondent pas.',
                    'required' => false,
                    'label' => 'Comfirmation : ',
                    'mapped' => false,
                    'constraints' => [
                        new Length(
                            [
                                'min' => 8,
                                'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères!',
                                // max length allowed by Symfony for security reasons
                                'max' => 50,
                                'maxMessage' => 'Votre mot de passe doit contenir au maximum {{ limit }} caractères!',
                            ]
                        ),
                    ],
                ]
            )
            ->add('Enregistrer', SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
