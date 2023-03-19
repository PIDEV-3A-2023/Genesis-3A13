<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarteBancaire
 *
 * @ORM\Table(name="carte_bancaire", uniqueConstraints={@ORM\UniqueConstraint(name="num_carte", columns={"num_carte"})})
 * @ORM\Entity
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


}
