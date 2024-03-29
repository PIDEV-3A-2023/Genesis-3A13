<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CartebancaireRepository;

/**
 * CarteBancaire
 *
 * @ORM\Table(name="carte_bancaire", uniqueConstraints={@ORM\UniqueConstraint(name="num_carte", columns={"num_carte"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CartebancaireRepository")
 */
class CarteBancaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_carte", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCarte;

    /**
     * @var int
     *
     * @ORM\Column(name="num_carte", type="integer", nullable=false)
     */
    private $numCarte;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var float
     *
     * @ORM\Column(name="solde", type="float", precision=10, scale=0, nullable=false)
     */
    private $solde;

    /**
     * @var int
     *
     * @ORM\Column(name="crypto_gramme", type="integer", nullable=false)
     */
    private $cryptoGramme;

    public function getIdCarte(): ?int
    {
        return $this->idCarte;
    }

    public function getNumCarte(): ?int
    {
        return $this->numCarte;
    }

    public function setNumCarte(int $numCarte): self
    {
        $this->numCarte = $numCarte;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getCryptoGramme(): ?int
    {
        return $this->cryptoGramme;
    }

    public function setCryptoGramme(int $cryptoGramme): self
    {
        $this->cryptoGramme = $cryptoGramme;

        return $this;
    }


}
