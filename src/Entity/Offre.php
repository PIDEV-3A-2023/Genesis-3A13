<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OffreRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Offre
 *
 * @ORM\Table(name="offre", indexes={@ORM\Index(name="livre", columns={"id_livre"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\OffreRepository")

 */
class Offre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_offre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idOffre;

    /**
     * @var string
     *
     * @ORM\Column(name="pourcentage_solde", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: 'pourcentage du solde est obligatoire!')]
    #[Assert\Length(max:3, maxMessage:'La pourcentage du solde ne peut pas dépasser {{ limit }} caractères et se termine par un "%".')]
    #[Assert\Length(min:2, minMessage:'La pourcentage du solde doit au minimum avoir {{ limit }} caractères et se termine par un "%".')]
    #[Assert\Regex(pattern:"/%$/", message:"La pourcentage du solde doit se terminer par un '%'")]

    private $pourcentageSolde;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_solde", type="float", precision=10, scale=0, nullable=false)
     */

    private $prixSolde;

    /**
     * @var Livre
     *
     * @ORM\ManyToOne(targetEntity="Livre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_livre", referencedColumnName="id_livre")
     * })
     */
    private $idLivre;

    public function getIdOffre(): ?int
    {
        return $this->idOffre;
    }

    public function getPourcentageSolde(): ?string
    {
        return $this->pourcentageSolde;
    }

    public function setPourcentageSolde(string $pourcentageSolde): self
    {
        $this->pourcentageSolde = $pourcentageSolde;

        return $this;
    }

    public function getPrixSolde(): ?float
    {
        return $this->prixSolde;
    }

    public function setPrixSolde(float $prixSolde): self
    {
        $this->prixSolde = $prixSolde;

        return $this;
    }

    public function getIdLivre(): ?Livre
    {
        return $this->idLivre;
    }

    public function setIdLivre(?Livre $idLivre): self
    {
        $this->idLivre = $idLivre;

        return $this;
    }
    
    
}
