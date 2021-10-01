<?php

namespace App\Entity;

use App\Repository\ProfSkillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfSkillRepository::class)
 */
class ProfSkill
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
     */
    private $Name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(1)
     */
    private $Percent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="profSkills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPercent(): ?int
    {
        return $this->Percent;
    }

    public function setPercent(int $Percent): self
    {
        $this->Percent = $Percent;

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
