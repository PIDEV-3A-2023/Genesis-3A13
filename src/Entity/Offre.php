<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offre
 *
 * @ORM\Table(name="offre", indexes={@ORM\Index(name="livre", columns={"id_livre"})})
 * @ORM\Entity
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
     #[Assert\Length(max: 3)]
    private $pourcentageSolde;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_soldé", type="float", precision=10, scale=0, nullable=false)
     */
    #[Assert\NotBlank(message: 'prix soldé est obligatoire!')]

    private $prixSoldé;

    /**
     * @var int
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

    public function getPrixSoldé(): ?float
    {
        return $this->prixSoldé;
    }

    public function setPrixSoldé(float $prixSoldé): self
    {
        $this->prixSoldé = $prixSoldé;

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
