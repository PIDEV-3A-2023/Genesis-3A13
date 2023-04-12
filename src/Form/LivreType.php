<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('datePub', DateType::class, [
                'label' => 'Date de publication',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control datepicker'],
            ])
            ->add('langue', TextType::class, [
                'label' => 'Langue',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('isbn', IntegerType::class, [
                'label' => 'ISBN',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('nbPages', IntegerType::class, [
                'label' => 'Nombre de pages',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('resume', TextType::class, [
                'label' => 'Résumé',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('prix', IntegerType::class, [
                'label' => 'Prix',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('image', FileType::class, [
                'required' => false, 
                'mapped' => false, 
                'label' => 'Image (JPG, PNG)', 
                'attr' => [
                    'accept' => '.jpg,.jpeg,.png' 
                ]
            ])
            ->add('idCategorie', EntityType::class, [
                'label' => 'Categorie',
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('idAuteur', EntityType::class, [
                'label' => 'Auteur',
                'class' => Utilisateur::class,
                'query_builder' => function (UtilisateurRepository $userRepository) {
                    return $userRepository->createQueryBuilder('u')
                        ->where('u.role = :role')
                        ->setParameter('role', 'Auteur');
                },
                'choice_label' => function (Utilisateur $auteur) {
                    return sprintf('%s %s', $auteur->getNom(), $auteur->getPrenom());
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
            'data_class' => Livre::class,
        ]);
    }
}
