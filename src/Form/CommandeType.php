<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status',ChoiceType::class, [
                'label' => 'Status de paiement',
                'choices' => [
                    'Payé' => 'paye',
                    'Non payé' => 'non_paye'
                ],
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'Etat',
                'choices' => [
                    'En cours' => 'encours',
                    'Livré' => 'livre',
                    'Annulé' => 'annuler',
                ],
            ]
            )
            
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
