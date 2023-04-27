<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
class Modifyprofiletype extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('motDePasse',PasswordType::class,[

                'required' => true,
                'constraints' => [new Regex([
                    'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/',
                    'message' => 'Votre mot de passe doit comporter au moins 8 caractères et contenir au moins une lettre et un numéro!',
                ]),
                new NotBlank([
                    'message' => 'Mot de passe Obligatoire!',
                ])
            ],

            ])
            ->add('numTelephone')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
