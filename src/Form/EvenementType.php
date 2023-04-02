<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'class' => 'form-control',
            ]
        ])
        ->add('date', DateType::class, [
            'label' => 'Date',
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control datepicker'],
        ]
        )
        ->add('heure', TimeType::class, [
            'label' => 'Heure',
            'widget' => 'single_text',
            'attr' => ['class' => 'form-control timepicker'],
        ])
        ->add('lieu', TextType::class, [
            'label' => 'Lieu',
             'attr' => [
                'class' => 'form-control',
            ]
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description', 'attr' => [
                'class' => 'form-control',
            ]
        ])
        ->add('nbTicket', IntegerType::class, [
            'label' => 'Nombre de tickets',
            'attr' => [
                'class' => 'form-control',
            ]
        ])
        ->add('idlivre', EntityType::class, [
            'label' => 'Livre',
            'class' => Livre::class,
            'choice_label' => 'titre',
            'attr' => [
                'class' => 'form-select',
            ]
        ])
        ->add('idauteur', EntityType::class, [
            'label' => 'Auteur',
            'class' => Utilisateur::class,
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->where('u.role LIKE :role')
                    ->setParameter('role', '%"Auteur"%')
                    ->orderBy('u.nom', 'ASC')
                    ->select("CONCAT(u.nom, ' ', u.prenom) AS value, u.idUtilisateur AS id");
            },
            'choice_label' => 'value',
            'attr' => [
                'class' => 'form-select',
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
