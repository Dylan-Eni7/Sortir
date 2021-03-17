<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Site;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'label' => "Nom de la sortie : "
            ])
            ->add('dateHeureDebut', DateTimeType::class,[
                'label' => "Date et heure de la sortie : ",
                'widget' => 'single_text',
            ])
            ->add('dateLimiteInscription', DateTimeType::class,[
                'label' => "Date limite d'inscription : ",
                'widget' => 'single_text',
            ])
            ->add('nbInscriptionsMax',IntegerType::class,[
                'label' => "nombre de places : "
            ])
            ->add('duree')
            ->add('infosSortie')

            ->add('site', EntityType::class, [
                'label' => "Ville organisatrice : ",
                // looks for choices from this entity
                'class' => Site::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'nom',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
//            ->add('ville', EntityType::class, [
//                'label' => "Ville : ",
//                // looks for choices from this entity
//                'class' => Ville::class,
//
//                // uses the User.username property as the visible option string
//                'choice_label' => 'nom',
//
//                // used to render a select box, check boxes or radios
//                // 'multiple' => true,
//                // 'expanded' => true,
//            ])
            ->add('lieu', EntityType::class, [
                // looks for choices from this entity
                'class' => Lieu::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'nom',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])

            ->add('Enregistrer', SubmitType::class)
            ->add('Publier', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
