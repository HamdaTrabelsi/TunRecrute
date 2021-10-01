<?php

namespace App\Entity;

use App\Repository\LanguageProfRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LanguageProfRepository::class)
 */
class LanguageProf
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
    private $LanguageName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ProfeciencyLvl;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $percent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="languageProfs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguageName(): ?string
    {
        return $this->LanguageName;
    }

    public function setLanguageName(string $LanguageName): self
    {
        $this->LanguageName = $LanguageName;

        return $this;
    }

    public function getProfeciencyLvl(): ?string
    {
        return $this->ProfeciencyLvl;
    }

    public function setProfeciencyLvl(string $ProfeciencyLvl): self
    {
        $this->ProfeciencyLvl = $ProfeciencyLvl;

        return $this;
    }

    public function getPercent(): ?int
    {
        return $this->percent;
    }

    public function setPercent(?int $percent): self
    {
        $this->percent = $percent;

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
