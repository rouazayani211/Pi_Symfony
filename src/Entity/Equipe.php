<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\EquipeRepository;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_equipe", type: "integer")]
    private int $idEquipe;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank]
    #[Assert\Type(type: "string")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]*$/",
        message: "Le nom doit contenir uniquement des lettres."
    )]
    private string $nom;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank]
    #[Assert\Type(type: "string")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]*$/",
        message: "La région doit contenir uniquement des lettres."
    )]
    private string $region;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank]
    #[Assert\Type(type: "string")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]*$/",
        message: "La ligue doit contenir uniquement des lettres."
    )]
    private string $ligue;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank]
    #[Assert\Type(type: "integer")]
    #[Assert\Range(
        min: 1,
        max: 20,
        notInRangeMessage: "Le classement doit être entre {{ min }} et {{ max }}."
    )]
    private int $classement;

    // Getters and setters for properties

    public function getIdEquipe(): int
    {
        return $this->idEquipe;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    public function getLigue(): string
    {
        return $this->ligue;
    }

    public function setLigue(string $ligue): void
    {
        $this->ligue = $ligue;
    }

    public function getClassement(): int
    {
        return $this->classement;
    }

    public function setClassement(int $classement): void
    {
        $this->classement = $classement;
    }
}
