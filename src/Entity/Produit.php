<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Range(
        min: 1,
        max: 9999999999,
        notInRangeMessage: "Le prix doit être compris entre {{ min }} et {{ max }}",
    )]
    private ?float $prix = null;



    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Length(
        min:3,
        max: 60,
        minMessage: 'Le nom doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s]*$/',
        message: 'Le nom ne peut pas contenir de caractères spéciaux.'
    )]
    private ?string $nom_produit = null;




    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Length(
        min:8,
        max: 60,
        minMessage: 'Le descripion doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Le descripion ne peut pas dépasser {{ limit }} caractères',
    )]
    private ?string $description = null;



    #[ORM\Column]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\GreaterThan(value: 0, message: "Le nombre de stock doit être supérieur à zéro")]
    private ?int $stock_disponible = null;

    #[ORM\OneToMany(targetEntity: Commandes::class, mappedBy: 'produit')]
    private Collection $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

   


   

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): static
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStockDisponible(): ?int
    {
        return $this->stock_disponible;
    }

    public function setStockDisponible(int $stock_disponible): static
    {
        $this->stock_disponible = $stock_disponible;

        return $this;
    }

    /**
     * @return Collection<int, Commandes>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commandes $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getProduit() === $this) {
                $commande->setProduit(null);
            }
        }

        return $this;
    }

   
   
}
