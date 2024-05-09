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
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_produit", type: "integer")]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    #[Assert\Range(
        min: 0.01,
        max: 9999999999,
        notInRangeMessage: "Le prix doit être compris entre {{ min }} et {{ max }}",
    )]
    private ?float $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFile = null; // Changed property name to imageFilename

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    #[Assert\Length(
        min: 3,
        max: 60,
        minMessage: 'Le nom doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s]*$/',
        message: 'Le nom ne peut pas contenir de caractères spéciaux.'
    )]
    private ?string $nom_produit  = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    #[Assert\Length(
        min: 8,
        max: 60,
        minMessage: 'La description doit comporter au moins {{ limit }} caractères',
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères',
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    #[Assert\GreaterThan(value: 0, message: "Le nombre de stock doit être supérieur à zéro")]
    private ?int $stockDisponible = null;


    
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

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getNomProduit(): ?string
    {
        return $this->nom_produit ;
    }

    public function setNomProduit(string $nom_produit ): self
    {
        $this->nom_produit  = $nom_produit ;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getStockDisponible(): ?int
    {
        return $this->stockDisponible;
    }

    public function setStockDisponible(int $stockDisponible): self
    {
        $this->stockDisponible = $stockDisponible;
        return $this;
    }

    public function getImageFile(): ?string // Added getter method for imageFilename
    {
        return $this->imageFile;
    }

    public function setImageFile(?string $imageFile): self // Added setter method for imageFilename
    {
        $this->imageFile = $imageFile;
        return $this;
    }

    /**
     * @return Collection<int, Commandes>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commandes $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
        }
        return $this;
    }

    public function removeCommande(Commandes $commande): self
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
