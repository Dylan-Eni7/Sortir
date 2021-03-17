<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateHeureDebut', DateType::class)
            ->add('dateLimiteInscription', DateType::class)
            ->add('nbInscriptionsMax', IntegerType::class)
            ->add('duree', IntegerType::class)
            ->add('infosSortie', TextareaType::class)
            ->add('site', EntityType::class)
            ->add('ville', EntityType::class)
            ->add('lieu', EntityType::class)
            ->add('rue', TextType::class)
            ->add('codepostal', IntegerType::class)
            ->add('latitude', IntegerType::class)
            ->add('longitude', IntegerType::class)
            ->add('Enregistrer', SubmitType::class)
            ->add('publier', SubmitType::class)
            ->add('annuler', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
