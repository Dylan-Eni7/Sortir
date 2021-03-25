<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Site;
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
                'widget' => 'single_text'
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
                'class' => Site::class,
                'choice_label' => 'nom',
                 'expanded' => true,
            ])

            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
            ])

            ->add('Enregistrer', SubmitType::class, [
                'attr' => ['style' => 'background-color : #00487f; border-color : #00487f; color : white;']
            ])
            ->add('Publier', SubmitType::class, [
                'attr' => ['style' => 'background-color : #00487f; border-color : #00487f; color : white;']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
