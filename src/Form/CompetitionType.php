<?php

namespace App\Form;

use App\Entity\Competition;
use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recompense', TextType::class,  [
                'attr' => [
                    'class' => 'form-control',
                ],
                'invalid_message_parameters' => [
                    '%class%' => 'is-invalid',
                ],
            ])
          /*  ->add('listePaticipants', TextType::class,  [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])*/
            ->add('lienCompetition', TextType::class,  [
                'label'=>'Lien',
                'attr' => [
                    'class' => 'form-control',
                ],
                'invalid_message_parameters' => [
                    '%class%' => 'is-invalid',
                ],
            ])
            ->add('nom', TextType::class,  [
                'attr' => [
                    'class' => 'form-control',
                ],
                'invalid_message_parameters' => [
                    '%class%' => 'is-invalid',
                ],
            ])
            ->add('dateDebut',  DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control datepicker'],
                'invalid_message_parameters' => [
                    '%class%' => 'is-invalid',
                ],
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control datepicker'],
                'invalid_message_parameters' => [
                    '%class%' => 'is-invalid',
                ],
            ])
            ->add('idlivre', EntityType::class, [
                'label'=>'Livre',
                'class' => Livre::class,
                'choice_label' => 'titre',
                'attr' => [
                    'class' => 'form-select',
                ],
                'invalid_message_parameters' => [
                    '%class%' => 'is-invalid',
                ],
            ])
            ->add('image', FileType::class, [
                'required' => false, 
                'mapped' => false, 
                'label' => 'Image (JPG, PNG)', 
                'attr' => [
                    'accept' => '.jpg,.jpeg,.png' 
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

            'data_class' => Competition::class,
        ]);
    }
}
