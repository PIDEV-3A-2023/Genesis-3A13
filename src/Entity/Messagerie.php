<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank
     * @Assert\Length(min=5, max=100)
     * @ORM\Column(name="message", type="string", length=255, nullable=false)
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating;

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

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->dateHeure;
    }

    public function setDateHeure(\DateTimeInterface $dateHeure): self
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

    
    public function getRating():  ?int
    {
        return $this->rating;
    }

    public function setRating($rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
