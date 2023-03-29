<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResulatQuiz
 *
 * @ORM\Table(name="resulat_quiz", indexes={@ORM\Index(name="client", columns={"id_client"}), @ORM\Index(name="quiz", columns={"id_quiz"})})
 * @ORM\Entity
 */
class ResulatQuiz
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_resulat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idResulat;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=false)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse_client", type="string", length=255, nullable=false)
     */
    private $reponseClient;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_quiz", referencedColumnName="id_quiz")
     * })
     */
    private $idQuiz;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id_utilisateur")
     * })
     */
    private $idClient;

    public function getIdResulat(): ?int
    {
        return $this->idResulat;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getReponseClient(): ?string
    {
        return $this->reponseClient;
    }

    public function setReponseClient(string $reponseClient): self
    {
        $this->reponseClient = $reponseClient;

        return $this;
    }

    public function getIdQuiz(): ?int
    {
        return $this->idQuiz;
    }

    public function setIdQuiz(?Quiz $idQuiz): self
    {
        $this->idQuiz = $idQuiz;

        return $this;
    }

    public function getIdClient(): ?int
    {
        return $this->idClient;
    }

    public function setIdClient(?int $idClient): self
    {
        $this->idClient = $idClient;

        return $this;
    }


}
