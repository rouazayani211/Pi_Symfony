<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "articles")]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Assert\Length(min: 20)]
    private $title;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Assert\Length(min: 10)]
    private $content;

    #[ORM\Column(type: "date")]
    #[Assert\NotNull]
    private $creationDate;

    #[ORM\Column(type: "string", length: 255)]
    private $imagePath;

    #[ORM\ManyToOne(targetEntity: Journalists::class)]
    #[ORM\JoinColumn(name: "journalist_id", referencedColumnName: "id")]
    private $journalist;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getJournalist(): ?Journalists
    {
        return $this->journalist;
    }

    public function setJournalist(?Journalists $journalist): self
    {
        $this->journalist = $journalist;

        return $this;
    }
}
