<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label'  => 'nom',
                'required'      => false,
                'mapped'        => false,
            ])
            ->add('dateHeureDebut',TextType::class,  [
                'label'    => 'Date Evenement (début)',
                'disabled'=>true,
                'required'      => false,
                'mapped'        => false
            ])
            ->add('dateHeureFin' ,TextType::class,  [
                'label'    => 'Date Evenement (fin)',
                'disabled'=>true,
                'required'      => false,
                'mapped'        => false
            ])
            ->add('organisateur', CheckboxType::class, [
                'label'         => 'Sorties dont je suis organisateur',
                'required'      => false,
                'mapped'        => false
            ])
            ->add('inscrit',CheckboxType::class,  [
                'label'         => 'Sorties auxquelle je suis inscrit',
                'required'      => false,
                'mapped'        => false,
                'disabled'=>true,
            ])
            ->add('pasInscrit',CheckboxType::class,  [
                'label'         => 'Sorties auxquelles je ne suis pas inscrit',
                'required'      => false,
                'mapped'        => false,
        'disabled'=>true,
            ])
            ->add('passed',CheckboxType::class,  [
                'label'         => 'Sorties passées',
                'required'      => false,
                'mapped'        => false,
                'disabled'=>true,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['style' => 'background-color : #00487f; border-color : #00487f; color : white;']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,

        ]);
    }
}
