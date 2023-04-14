<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\QuestionRepository;
/**
 * Question
 *
 * @ORM\Table(name="question", indexes={@ORM\Index(name="quiz", columns={"id_quiz"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_question", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idQuestion;
/**
 * @var string
 *
 * @ORM\Column(name="question", type="string", length=255, nullable=false)
 * @Assert\NotBlank(message="Le champ question est obligatoire")
 * @Assert\Length(
 *      min = 10,
 *      max = 255,
 *      minMessage = "Le champ question doit contenir au moins {{ limit }} caractères",
 *      maxMessage = "Le champ question doit contenir moins de {{ limit }} caractères"
 * )
 * @Assert\Regex(
 *      pattern="/\?$/",
 *      match=true,
 *      message="La question doit se terminer par un point d'interrogation"
 * )
 */
private $question;

/**
 * @var string
 *
 * @ORM\Column(name="choix1", type="string", length=255, nullable=false)
 * @Assert\NotBlank(message="Le champ choix1 est obligatoire")
 */
private $choix1;

/**
 * @var string
 *
 * @ORM\Column(name="choix2", type="string", length=255, nullable=false)
 * @Assert\NotBlank(message="Le champ choix2 est obligatoire")
 */
private $choix2;

/**
 * @var string
 *
 * @ORM\Column(name="choix3", type="string", length=255, nullable=false)
 * @Assert\NotBlank(message="Le champ choix3 est obligatoire")
 */
private $choix3;

/**
 * @var string
 *
 * @ORM\Column(name="reponse_correct", type="string", length=255, nullable=false)
 * @Assert\NotBlank(message="Le champ réponse correcte est obligatoire")
 * @Assert\Choice(
 *     choices={"choix1", "choix2", "choix3"},
 *     message="La réponse correcte doit être l'un des choix proposés"
 * )
 */
private $reponseCorrect;


    /**
     * @var Quiz
     *
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_quiz", referencedColumnName="id_quiz")
     * })
     */
    private $idQuiz;

    public function getIdQuestion(): ?int
    {
        return $this->idQuestion;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getChoix1(): ?string
    {
        return $this->choix1;
    }

    public function setChoix1(string $choix1): self
    {
        $this->choix1 = $choix1;

        return $this;
    }

    public function getChoix2(): ?string
    {
        return $this->choix2;
    }

    public function setChoix2(string $choix2): self
    {
        $this->choix2 = $choix2;

        return $this;
    }

    public function getChoix3(): ?string
    {
        return $this->choix3;
    }

    public function setChoix3(string $choix3): self
    {
        $this->choix3 = $choix3;

        return $this;
    }

    public function getReponseCorrect(): ?string
    {
        return $this->reponseCorrect;
    }

    public function setReponseCorrect(string $reponseCorrect): self
    {
        $this->reponseCorrect = $reponseCorrect;

        return $this;
    }

    public function getIdQuiz(): ?Quiz
    {
        return $this->idQuiz;
    }

    public function setIdQuiz(?Quiz $idQuiz): self
    {
        $this->idQuiz = $idQuiz;

        return $this;
    }


}
