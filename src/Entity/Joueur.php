<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\JoueurRepository;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

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
        message: "Le prénom doit contenir uniquement des lettres."
    )]
    private string $prenom;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank]
    #[Assert\Type(type: "integer")]
    #[Assert\Range(
        min: 15,
        max: 50,
        notInRangeMessage: "L'âge doit être entre {{ min }} et {{ max }} ans."
    )]
    private int $age;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank]
    #[Assert\Type(type: "integer")]
    #[Assert\Range(
        min: 1,
        max: 99,
        notInRangeMessage: "Le numéro doit être entre {{ min }} et {{ max }}."
    )]
    private int $numero;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank]
    #[Assert\Type(type: "string")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s]*$/",
        message: "La position doit contenir uniquement des lettres."
    )]
    private string $position;

    #[ORM\ManyToOne(targetEntity: Equipe::class)]
    #[ORM\JoinColumn(name: "id_Equipe", referencedColumnName: "id_equipe", nullable: false)]
    private Equipe $equipe;


    public function __construct()
    {
        $this->id = 0; 
    }
    


    public function getId(): int
    {
        return $this->id;
    }

     public function getNom(): string
     {
         return $this->nom;
     }
 
     public function setNom(string $nom): void
     {
         $this->nom = $nom;
     }
 
     public function getPrenom(): string
     {
         return $this->prenom;
     }
 
     public function setPrenom(string $prenom): void
     {
         $this->prenom = $prenom;
     }
 
     public function getAge(): int
     {
         return $this->age;
     }
 
     public function setAge(int $age): void
     {
         $this->age = $age;
     }
 
     public function getNumero(): int
     {
         return $this->numero;
     }
 
     public function setNumero(int $numero): void
     {
         $this->numero = $numero;
     }
 
     public function getPosition(): string
     {
         return $this->position;
     }
 
     public function setPosition(string $position): void
     {
         $this->position = $position;
     }
 
     public function getEquipe(): Equipe
     {
         return $this->equipe;
     }
 
     public function setEquipe(Equipe $equipe): void
     {
         $this->equipe = $equipe;
     }

     public function getid_Equipe(): ?int
     {
         return $this->id_Equipe;
     }
 
     public function setid_Equipe(int $id_Equipe): void
     {
         $this->id_Equipe = $id_Equipe;
     }
     
}
