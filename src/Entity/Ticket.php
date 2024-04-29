<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdTicket", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idticket;

    /**
     * @var int
     *
     * @ORM\Column(name="IdReservation", type="integer", nullable=false)
     */
    private $idreservation;

    /**
     * @var int
     *
     * @ORM\Column(name="NumSiege", type="integer", nullable=false)
     */
    private $numsiege;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="DateEvenement", type="string", length=225, nullable=false)
     */
    private $dateevenement;

    /**
     * @var string
     *
     * @ORM\Column(name="StatutTicket", type="string", length=225, nullable=false)
     */
    private $statutticket;

    public function getIdticket(): ?int
    {
        return $this->idticket;
    }

    public function getIdreservation(): ?int
    {
        return $this->idreservation;
    }

    public function setIdreservation(int $idreservation): static
    {
        $this->idreservation = $idreservation;

        return $this;
    }

    public function getNumsiege(): ?int
    {
        return $this->numsiege;
    }

    public function setNumsiege(int $numsiege): static
    {
        $this->numsiege = $numsiege;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateevenement(): ?string
    {
        return $this->dateevenement;
    }

    public function setDateevenement(string $dateevenement): static
    {
        $this->dateevenement = $dateevenement;

        return $this;
    }

    public function getStatutticket(): ?string
    {
        return $this->statutticket;
    }

    public function setStatutticket(string $statutticket): static
    {
        $this->statutticket = $statutticket;

        return $this;
    }


}
