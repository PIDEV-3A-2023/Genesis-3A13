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
     * @var \Quiz
     *
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_quiz", referencedColumnName="id_quiz")
     * })
     */
    private $idQuiz;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id_utilisateur")
     * })
     */
    private $idClient;


}
