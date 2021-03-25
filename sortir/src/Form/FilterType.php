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
                'empty_data'    => null,
                'mapped'        => false,
            ])
            ->add('dateHeureDebut',TextType::class,  [
                'label'    => 'Date Evenement (début)',
                'attr' => [
                    'class' => 'form-control datetimepicker-input',
                    'data-toggle'=>'datetimepicker',
                    'data-target'=>'#filter_start'
                ],
                'required'      => false,
                'empty_data'    => null,
                'mapped'        => false
            ])
            ->add('dateHeureFin' ,TextType::class,  [
                'label'    => 'Date Evenement (fin)',
                'attr' => [
                    'class' => 'form-control datetimepicker-input',
                    'data-toggle'=>'datetimepicker',
                    'data-target'=>'#filter_close'
                ],
                'required'      => false,
                'empty_data'    => null,
                'mapped'        => false
            ])
            ->add('organisateur', CheckboxType::class, [
                'label'         => 'Sorties dont je suis organisateur',
                'required'      => false,
                'empty_data'    => null,
                'mapped'        => false
            ])
            ->add('inscrit',CheckboxType::class,  [
                'label'         => 'Sorties auxquelle je suis inscrit',
                'required'      => false,
                'empty_data'    => null,
                'mapped'        => false
            ])
            ->add('pasInscrit',CheckboxType::class,  [
                'label'         => 'Sorties auxquelles je ne suis pas inscrit',
                'required'      => false,
                'empty_data'    => null,
                'mapped'        => false
            ])
            ->add('passed',CheckboxType::class,  [
                'label'         => 'Sorties passées',
                'required'      => false,
                'empty_data'    => null,
                'mapped'        => false
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
