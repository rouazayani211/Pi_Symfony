<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reserevation
 *
 * @ORM\Table(name="reserevation")
 * @ORM\Entity
 */
class Reserevation
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdReservation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idreservation;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     */
    private $iduser;

    /**
     * @var string
     *
     * @ORM\Column(name="dateDeReservation", type="string", length=50, nullable=false)
     */
    private $datedereservation;

    /**
     * @var int
     *
     * @ORM\Column(name="nombreTicket", type="integer", nullable=false)
     */
    private $nombreticket;

    /**
     * @var string
     *
     * @ORM\Column(name="statutReservation", type="string", length=50, nullable=false)
     */
    private $statutreservation;

    public function getIdreservation(): ?int
    {
        return $this->idreservation;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getDatedereservation(): ?string
    {
        return $this->datedereservation;
    }

    public function setDatedereservation(string $datedereservation): static
    {
        $this->datedereservation = $datedereservation;

        return $this;
    }

    public function getNombreticket(): ?int
    {
        return $this->nombreticket;
    }

    public function setNombreticket(int $nombreticket): static
    {
        $this->nombreticket = $nombreticket;

        return $this;
    }

    public function getStatutreservation(): ?string
    {
        return $this->statutreservation;
    }

    public function setStatutreservation(string $statutreservation): static
    {
        $this->statutreservation = $statutreservation;

        return $this;
    }


}
