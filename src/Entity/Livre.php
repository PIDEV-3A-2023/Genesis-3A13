<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\LivreRepository;

/**
 * Livre
 *
 * @ORM\Table(name="livre", indexes={@ORM\Index(name="auteur", columns={"id_auteur"}), @ORM\Index(name="categorie", columns={"id_categorie"})})
 * @ORM\Entity
 * @UniqueEntity(fields={"titre"}, message="Ce nom est déjà utilisé.")
 * @ORM\Entity(repositoryClass="App\Repository\LivreRepository")
 */
class Livre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_livre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLivre;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: 'Titre obligatoire!')]
    #[Assert\Length(max:255, maxMessage:'Le titre ne peut pas dépasser {{ limit }} caractères.')]
    #[Assert\Length(min:5, minMessage:'Le titre doit au minimum avoir {{ limit }} caractères.')]
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_pub", type="date", nullable=false)
     */
    #[Assert\NotBlank(message: 'date de publication obligatoire!')]
    private $datePub;

    /**
     * @var string
     *
     * @ORM\Column(name="langue", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank(message: 'Langue obligatoire!')]
    #[Assert\Length(max:255, maxMessage:'La Langue ne peut pas dépasser {{ limit }} caractères.')]
    #[Assert\Length(min:5, minMessage:'La Langue doit au minimum avoir {{ limit }} caractères.')]
    private $langue;

    /**
     * @var int
     *
     * @ORM\Column(name="isbn", type="integer", nullable=false)
     */
    #[Assert\NotBlank(message: 'le n° de ISBN obligatoire!')]
    #[Assert\Positive(message : "Le n° de ISBN  doit être positif!")]
    private $isbn;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_pages", type="integer", nullable=false)
     */
    #[Assert\NotBlank(message: 'nombre de pages obligatoire!')]
    #[Assert\Range(min : 10,max :500, notInRangeMessage:'Le nombre de pages doit être compris entre {{ min }} et {{ max }}!')]
    #[Assert\Positive(message : "Le nombre de pages doit être positif!")]
    private $nbPages;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="text", length=65535, nullable=false)
     */
    #[Assert\NotBlank(message: 'Résumé obligatoire!')]
    #[Assert\Length(max:255, maxMessage:'La Résumé  ne peut pas dépasser {{ limit }} caractères.')]
    #[Assert\Length(min:5, minMessage:'La Résumé doit au minimum avoir {{ limit }} caractères.')]
    private $resume;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    #[Assert\NotBlank(message: 'Prix est obligatoire!')]
    #[Assert\Positive(message : "Prix doit être positif!")]
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="blob", length=0, nullable=false)
     */

    private $image;

    /**
     * @var Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id_categorie")
     * })
     */
    #[Assert\NotBlank(message: 'Categorie obligatoire!')]
    private $idCategorie;

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

    public function getIdLivre(): ?int
    {
        return $this->idLivre;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDatePub(): ?\DateTimeInterface
    {
        return $this->datePub;
    }

    public function setDatePub(\DateTimeInterface $datePub): self
    {
        $this->datePub = $datePub;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getIsbn(): ?int
    {
        return $this->isbn;
    }

    public function setIsbn(int $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getNbPages(): ?int
    {
        return $this->nbPages;
    }

    public function setNbPages(int $nbPages): self
    {
        $this->nbPages = $nbPages;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

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


}
