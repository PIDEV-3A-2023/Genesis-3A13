<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


use App\Repository\EvenementRepository;


/**
 * Evenement
 *
 * @ORM\Table(name="evenement", indexes={@ORM\Index(name="auteur", columns={"id_auteur"}), @ORM\Index(name="livre", columns={"id_livre"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_evenement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEvenement;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * 
     */
    #[Assert\NotBlank(message: 'nom obligatoire!')]
    #[Assert\Length(max:255, maxMessage:'Le nom ne peut pas dépasser {{ limit }} caractères.')]
    #[Assert\Length(min:5, maxMessage:'Le nom ne peut pas etre inférieure {{ limit }} caractères.')]
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    #[Assert\NotBlank(message: 'date obligatoire!')]
    #[Assert\GreaterThanOrEqual("today",message: 'La date doit être supérieure ou égale à la date d\'aujourd\'hui!')]
    
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure", type="time", nullable=false)
     */
    #[Assert\NotBlank(message: 'heure obligatoire!')]
    
    
    private $heure;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: 'Lieu obligatoire!')]
    #[Assert\Length(max:255, maxMessage:'Le lieu ne peut pas dépasser {{ limit }} caractères.')]
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: 'Description obligatoire!')]
    #[Assert\Length(max:255, maxMessage:'La description ne peut pas dépasser {{ limit }} caractères.')]
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_ticket", type="integer", nullable=false)
     */
    #[Assert\NotBlank(message: 'nombre de ticket obligatoire!')]
    #[Assert\Range(min : 10,max :500, notInRangeMessage:'Le nombre de ticket doit être compris entre {{ min }} et {{ max }}!')]
    #[Assert\Positive(message : "Le nombre de ticket doit être positif!")]
    private $nbTicket;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_auteur", referencedColumnName="id_utilisateur")
     * })
     */
    #[Assert\NotBlank(message: 'Auteur obligatoire!')]
    private $idAuteur;

    /**
     * @var Livre
     *
     * @ORM\ManyToOne(targetEntity="Livre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_livre", referencedColumnName="id_livre")
     * })
     */
    #[Assert\NotBlank(message: 'Livre obligatoire!')]
    private $idLivre;

    public function getIdEvenement(): ?int
    {
        return $this->idEvenement;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbTicket(): ?int
    {
        return $this->nbTicket;
    }

    public function setNbTicket(int $nbTicket): self
    {
        $this->nbTicket = $nbTicket;

        return $this;
    }

    public function getIdAuteur(): ?Utilisateur
    {
        return $this->idAuteur;
    }

    public function setIdAuteur(?Utilisateur $idAuteur): self
    {
        $this->idAuteur = $idAuteur;

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
