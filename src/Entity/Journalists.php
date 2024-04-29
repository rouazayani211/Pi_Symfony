<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Journalists
 *
 * @ORM\Table(name="journalists")
 * @ORM\Entity
 */
class Journalists
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="education", type="string", length=255, nullable=true)
     */
    private $education;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="independent", type="boolean", nullable=true)
     */
    private $independent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="current_company", type="string", length=255, nullable=true)
     */
    private $currentCompany;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEducation(): ?string
    {
        return $this->education;
    }

    public function setEducation(?string $education): static
    {
        $this->education = $education;

        return $this;
    }

    public function isIndependent(): ?bool
    {
        return $this->independent;
    }

    public function setIndependent(?bool $independent): static
    {
        $this->independent = $independent;

        return $this;
    }

    public function getCurrentCompany(): ?string
    {
        return $this->currentCompany;
    }

    public function setCurrentCompany(?string $currentCompany): static
    {
        $this->currentCompany = $currentCompany;

        return $this;
    }


}
