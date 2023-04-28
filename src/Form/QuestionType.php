<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question')
            ->add('choix1')
            ->add('choix2')
            ->add('choix3')
            ->add('reponseCorrect')
            ->add('idQuiz', EntityType::class, [
                'label'=>'Compétition',
                'class' => Quiz::class,
                'choice_label' => function ($quiz) {
                    return $quiz->getIdCompetition()->getNom() . ' - ' . $quiz->getIdLivre()->getTitre();
                },
                'attr' => [
                    'class' => 'form-select',
                ],
                'invalid_message_parameters' => [
                    '%class%' => 'is-invalid',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
