<?php

namespace App\Entity;

use App\Repository\EducationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EducationRepository::class)
 */
class Education
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex("/^[a-zA-Z ]+$/")
     */
    private $DiplomaTitle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex("/^[a-zA-Z ]+$/")
     */
    private $InstitutionName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(1990)
     */
    private $startYear;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(propertyPath="startYear")
     */
    private $endYear;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex("/^\w+/")
     */
    private $Description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="education")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiplomaTitle(): ?string
    {
        return $this->DiplomaTitle;
    }

    public function setDiplomaTitle(string $DiplomaTitle): self
    {
        $this->DiplomaTitle = $DiplomaTitle;

        return $this;
    }

    public function getInstitutionName(): ?string
    {
        return $this->InstitutionName;
    }

    public function setInstitutionName(string $InstitutionName): self
    {
        $this->InstitutionName = $InstitutionName;

        return $this;
    }

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(?int $startYear): self
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): self
    {
        $this->endYear = $endYear;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
