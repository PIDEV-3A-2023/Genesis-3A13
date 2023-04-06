<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fidelite
 *
 * @ORM\Table(name="fidelite", indexes={@ORM\Index(name="client", columns={"id_client"})})
 * @ORM\Entity
 */
class Fidelite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_fidelite", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idFidelite;

    /**
     * @var int
     *
     * @ORM\Column(name="total_achat", type="integer", nullable=false)
     */
    #[Assert\NotBlank(message: 'total achat est obligatoire!')]

    private $totalAchat;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=0, nullable=false)
     */

    #[Assert\NotBlank(message: 'type de fidelite est obligatoire!')]
    private $type;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id_utilisateur")
     * })
     */
    private $idClient;

    public function getIdFidelite(): ?int
    {
        return $this->idFidelite;
    }

    public function getTotalAchat(): ?int
    {
        return $this->totalAchat;
    }

    public function setTotalAchat(int $totalAchat): self
    {
        $this->totalAchat = $totalAchat;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
