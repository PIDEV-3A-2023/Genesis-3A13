<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz", indexes={@ORM\Index(name="livre", columns={"id_livre"}), @ORM\Index(name="competition", columns={"id_competition"})})
 * @ORM\Entity
 */
class Quiz
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_quiz", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idQuiz;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Competition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_competition", referencedColumnName="id_competition")
     * })
     */
    private $idCompetition;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Livre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_livre", referencedColumnName="id_livre")
     * })
     */
    private $idLivre;

    public function getIdQuiz(): ?int
    {
        return $this->idQuiz;
    }

    public function getIdCompetition(): ?int
    {
        return $this->idCompetition;
    }

    public function setIdCompetition(?int $idCompetition): self
    {
        $this->idCompetition = $idCompetition;

        return $this;
    }

    public function getIdLivre(): ?int
    {
        return $this->idLivre;
    }

    public function setIdLivre(?int $idLivre): self
    {
        $this->idLivre = $idLivre;

        return $this;
    }


}
