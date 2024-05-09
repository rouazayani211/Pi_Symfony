<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    private ?int $NumSiege = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Range(
        min: 1,
        max: 500,
        notInRangeMessage: "Le prix doit être compris entre {{ min }} et {{ max }}",
    )]
    private ?float $prix = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dateEvenement = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    private ?string $statutTicket = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?Reservation $idReservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumSiege(): ?int
    {
        return $this->NumSiege;
    }

    public function setNumSiege(int $NumSiege): static
    {
        $this->NumSiege = $NumSiege;

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

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->dateEvenement;
    }

    public function setDateEvenement(\DateTimeInterface $dateEvenement): static
    {
        $this->dateEvenement = $dateEvenement;

        return $this;
    }

    public function getStatutTicket(): ?string
    {
        return $this->statutTicket;
    }

    public function setStatutTicket(string $statutTicket): static
    {
        $this->statutTicket = $statutTicket;

        return $this;
    }

    public function getIdReservation(): ?Reservation
    {
        return $this->idReservation;
    }

    public function setIdReservation(?Reservation $idReservation): static
    {
        $this->idReservation = $idReservation;

        return $this;
    }
}
