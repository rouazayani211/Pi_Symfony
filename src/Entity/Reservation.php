<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDeReservation;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(0)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: "Le nombre de ticket doit être compris entre {{ min }} et {{ max }}",
    )]
    private ?int $nombreTicket = null;

    #[ORM\Column(length: 50)]
    private ?string $statutReservation = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $idUser = null;

    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'idReservation')]
    private Collection $tickets;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeReservation(): ?\DateTimeInterface
    {
        return $this->dateDeReservation;
    }

    public function setDateDeReservation(\DateTimeInterface $dateDeReservation): static
    {
        $this->dateDeReservation = $dateDeReservation;

        return $this;
    }

    public function getNombreTicket(): ?int
    {
        return $this->nombreTicket;
    }

    public function setNombreTicket(int $nombreTicket): static
    {
        $this->nombreTicket = $nombreTicket;

        return $this;
    }

    public function getStatutReservation(): ?string
    {
        return $this->statutReservation;
    }

    public function setStatutReservation(string $statutReservation): static
    {
        $this->statutReservation = $statutReservation;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setIdReservation($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getIdReservation() === $this) {
                $ticket->setIdReservation(null);
            }
        }

        return $this;
    }
}
