<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panierlivre
 *
 * @ORM\Table(name="panierlivre", indexes={@ORM\Index(name="fk_panier", columns={"id_panier"}), @ORM\Index(name="fk_livre", columns={"id_livre"})})
 * @ORM\Entity
 */
class Panierlivre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_panier_livre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPanierLivre;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \Livre
     *
     * @ORM\ManyToOne(targetEntity="Livre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_livre", referencedColumnName="id_livre")
     * })
     */
    private $idLivre;

    /**
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_panier", referencedColumnName="id_panier")
     * })
     */
    private $idPanier;


}
