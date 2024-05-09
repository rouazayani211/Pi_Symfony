<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "journalists")]
class Journalists
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Assert\Length(min: 7)]
    private $name;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Assert\Length(min: 10)]
    private $education;

    #[ORM\Column(type: "boolean")]
    private $independent;

    #[ORM\Column(type: "string", length: 255, options: ["default" => "Unemployed"])]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Assert\Length(min: 7)]
    private $currentCompany;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEducation(): ?string
    {
        return $this->education;
    }

    public function setEducation(?string $education): self
    {
        $this->education = $education;

        return $this;
    }

    public function isIndependent(): ?bool
    {
        return $this->independent;
    }

    public function setIndependent(?bool $independent): self
    {
        $this->independent = $independent;

        return $this;
    }

    public function getCurrentCompany(): ?string
    {
        return $this->currentCompany;
    }

    public function setCurrentCompany(?string $currentCompany): self
    {
        $this->currentCompany = $currentCompany;

        return $this;
    }
}
