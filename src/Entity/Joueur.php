<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\JoueurRepository;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JoueurRepository")*/
#[ORM\Entity(repositoryClass: JoueurRepository::class)]

class Joueur
{
 /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z\s]*$/",
     *      message="Le nom doit contenir uniquement des lettres."
     * )
     */
    private string $nom;

    /**
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z\s]*$/",
     *      message="Le prénom doit contenir uniquement des lettres."
     * )
     */
    private string $prenom;

    /**
     * @ORM\Column(name="age", type="integer")
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\Range(
     *      min = 15,
     *      max = 50,
     *      notInRangeMessage = "L'âge doit être entre {{ min }} et {{ max }} ans.",
     * )
     */
    private int $age;

    /**
     * @ORM\Column(name="numero", type="integer")
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 99,
     *      notInRangeMessage = "Le numéro doit être entre {{ min }} et {{ max }}.",
     * )
     */
    private int $numero;

    /**
     * @ORM\Column(name="position", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     * @Assert\Regex(
     *      pattern="/^[a-zA-Z\s]*$/",
     *      message="La position doit contenir uniquement des lettres."
     * )
     */
    private string $position;

    /**
     * @ORM\Column(name="id_Equipe", type="integer", length=255)
     */
    private int $id_Equipe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipe")
     * @ORM\JoinColumn(name="id_Equipe", referencedColumnName="id_equipe", nullable=false)
     */
    private Equipe $equipe;

    // Getters and setters can be added here
     // Getters and setters
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
