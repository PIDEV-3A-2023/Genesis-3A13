<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messagerie
 *
 * @ORM\Table(name="messagerie", indexes={@ORM\Index(name="destinataire", columns={"id_destinataire"})})
 * @ORM\Entity
 */
class Messagerie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_messagerie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMessagerie;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=false)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="date_heure", type="string", length=20, nullable=false)
     */
    private $dateHeure;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_destinataire", referencedColumnName="id_utilisateur")
     * })
     */
    private $idDestinataire;

    public function getIdMessagerie(): ?int
    {
        return $this->idMessagerie;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDateHeure(): ?string
    {
        return $this->dateHeure;
    }

    public function setDateHeure(string $dateHeure): self
    {
        $this->dateHeure = $dateHeure;

        return $this;
    }

    public function getIdDestinataire(): ?Utilisateur
    {
        return $this->idDestinataire;
    }

    public function setIdDestinataire(?Utilisateur $idDestinataire): self
    {
        $this->idDestinataire = $idDestinataire;

        return $this;
    }


}
