<?php

namespace App\Entity;

use App\Repository\TerrainRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TerrainRepository::class)]
class Terrain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "ID_terrain", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du terrain ne peut pas être vide.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom du terrain ne peut pas dépasser 255 caractères."
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La localisation ne peut pas être vide.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "La localisation ne peut pas dépasser 255 caractères."
    )]
    private ?string $localisation = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La capacité ne peut pas être vide.")]
    #[Assert\Positive(message: "La capacité doit être un nombre positif.")]
    private ?int $capacite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }
}
