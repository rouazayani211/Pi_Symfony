<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_commande", type: "integer")]
    private ?int $idc = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: "produit_id", referencedColumnName: "id_produit")]
    private ?Produit $produit = null;

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?string $modePayement = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Length(
        min:3,
        max: 60,
        minMessage: 'L Adresse doit comporter au moins {{ limit }} caractères',
        maxMessage: 'L Adresse ne peut pas dépasser {{ limit }} caractères',
    )]
    private ?string $Adresse = null;

  
    
    #[ORM\Column]
    private ?float $montant = null;



    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float  $montant): static
    {
        $this->montant =$montant;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->idc;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }


    // In Commandes entity

    public function getModePayement(): ?string
    {
        return $this->modePayement;
    }

    public function setModePayement(string $modePayement): static
    {
        $this->modePayement = $modePayement;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): static
    {
        $this->Adresse = $Adresse;

        return $this;
    }


}

