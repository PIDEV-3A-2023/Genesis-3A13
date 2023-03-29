<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Competition
 *
 * @ORM\Table(name="competition", indexes={@ORM\Index(name="livre", columns={"id_livre"})})
 * @ORM\Entity
 */
class Competition
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_competition", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCompetition;

    /**
     * @var string
     *
     * @ORM\Column(name="recompense", type="string", length=255, nullable=false)
     */
    private $recompense;

    /**
     * @var string
     *
     * @ORM\Column(name="liste_paticipants", type="text", length=65535, nullable=false)
     */
    private $listePaticipants;

    /**
     * @var string
     *
     * @ORM\Column(name="lien_competition", type="string", length=255, nullable=false)
     */
    private $lienCompetition;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=false)
     */
    private $dateFin;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Livre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_livre", referencedColumnName="id_livre")
     * })
     */
    private $idLivre;

    public function getIdCompetition(): ?int
    {
        return $this->idCompetition;
    }

    public function getRecompense(): ?string
    {
        return $this->recompense;
    }

    public function setRecompense(string $recompense): self
    {
        $this->recompense = $recompense;

        return $this;
    }

    public function getListePaticipants(): ?string
    {
        return $this->listePaticipants;
    }

    public function setListePaticipants(string $listePaticipants): self
    {
        $this->listePaticipants = $listePaticipants;

        return $this;
    }

    public function getLienCompetition(): ?string
    {
        return $this->lienCompetition;
    }

    public function setLienCompetition(string $lienCompetition): self
    {
        $this->lienCompetition = $lienCompetition;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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
