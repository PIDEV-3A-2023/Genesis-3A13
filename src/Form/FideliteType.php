<?php

namespace App\Form;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\UtilisateurRepository;

use App\Entity\Fidelite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FideliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('totalAchat')
            ->add('type')
            ->add('idClient', EntityType::class, [
                'label' => 'Client',
                'class' => Utilisateur::class,
                'query_builder' => function (UtilisateurRepository $userRepository) {
                    return $userRepository->createQueryBuilder('u')
                        ->where('u.role = :role')
                        ->setParameter('role', 'Client');
                },
                'choice_label' => function (Utilisateur $client) {
                    return sprintf('%s %s', $client->getNom(), $client->getPrenom());
                },
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fidelite::class,
        ]);
    }
}
