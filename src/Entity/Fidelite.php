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
    private $totalAchat;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=0, nullable=false)
     */
    private $type;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id_utilisateur")
     * })
     */
    private $idClient;


}
