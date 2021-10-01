<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^[a-zA-Z ]+$/")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^[a-zA-Z ]+$/")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^[0-9 ]+$/")
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $compname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="User")
     * @ORM\OrderBy({"posted" = "desc"})
     */
    private $annonces;

    /**
     * @ORM\OneToMany(targetEntity=Candidature::class, mappedBy="User")
     */
    private $candidatures;

    /**
     * @ORM\OneToMany(targetEntity=Signal::class, mappedBy="user_id")
     */
    private $signals;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $aboutme;

    /**
     * @ORM\OneToMany(targetEntity=ProfSkill::class, mappedBy="user")
     */
    private $profSkills;

    /**
     * @ORM\OneToMany(targetEntity=WorkExp::class, mappedBy="user")
     */
    private $workExps;

    /**
     * @ORM\OneToMany(targetEntity=Education::class, mappedBy="user")
     */
    private $education;

    /**
     * @ORM\OneToMany(targetEntity=LanguageProf::class, mappedBy="user")
     */
    private $languageProfs;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $sex;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\DateTime(format="d/m/Y",message="date must match the following format: Day/Month/Year")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex("/^[a-zA-Z ]+$/")
     */
    private $nationality;

    /**
     * @ORM\OneToOne(targetEntity=SocialMedia::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $socialMedia;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cvFilename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coverLetterFilename;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;
    



    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->signals = new ArrayCollection();
        $this->profSkills = new ArrayCollection();
        $this->workExps = new ArrayCollection();
        $this->education = new ArrayCollection();
        $this->languageProfs = new ArrayCollection();
        $this->socialMedia = new ArrayCollection();
        $this->candit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getCompname(): ?string
    {
        return $this->compname;
    }

    public function setCompname(?string $compname): self
    {
        $this->compname = $compname;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setUser($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getUser() === $this) {
                $annonce->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Candidature[]
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setUser($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getUser() === $this) {
                $candidature->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Signal[]
     */
    public function getSignals(): Collection
    {
        return $this->signals;
    }

    public function addSignal(Signal $signal): self
    {
        if (!$this->signals->contains($signal)) {
            $this->signals[] = $signal;
            $signal->setUserId($this);
        }

        return $this;
    }

    public function removeSignal(Signal $signal): self
    {
        if ($this->signals->removeElement($signal)) {
            // set the owning side to null (unless already changed)
            if ($signal->getUserId() === $this) {
                $signal->setUserId(null);
            }
        }

        return $this;
    }

    public function getAboutme(): ?string
    {
        return $this->aboutme;
    }

    public function setAboutme(?string $aboutme): self
    {
        $this->aboutme = $aboutme;

        return $this;
    }

    /**
     * @return Collection|ProfSkill[]
     */
    public function getProfSkills(): Collection
    {
        return $this->profSkills;
    }

    public function addProfSkill(ProfSkill $profSkill): self
    {
        if (!$this->profSkills->contains($profSkill)) {
            $this->profSkills[] = $profSkill;
            $profSkill->setUser($this);
        }

        return $this;
    }

    public function removeProfSkill(ProfSkill $profSkill): self
    {
        if ($this->profSkills->removeElement($profSkill)) {
            // set the owning side to null (unless already changed)
            if ($profSkill->getUser() === $this) {
                $profSkill->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WorkExp[]
     */
    public function getWorkExps(): Collection
    {
        return $this->workExps;
    }

    public function addWorkExp(WorkExp $workExp): self
    {
        if (!$this->workExps->contains($workExp)) {
            $this->workExps[] = $workExp;
            $workExp->setUser($this);
        }

        return $this;
    }

    public function removeWorkExp(WorkExp $workExp): self
    {
        if ($this->workExps->removeElement($workExp)) {
            // set the owning side to null (unless already changed)
            if ($workExp->getUser() === $this) {
                $workExp->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Education[]
     */
    public function getEducation(): Collection
    {
        return $this->education;
    }

    public function addEducation(Education $education): self
    {
        if (!$this->education->contains($education)) {
            $this->education[] = $education;
            $education->setUser($this);
        }

        return $this;
    }

    public function removeEducation(Education $education): self
    {
        if ($this->education->removeElement($education)) {
            // set the owning side to null (unless already changed)
            if ($education->getUser() === $this) {
                $education->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LanguageProf[]
     */
    public function getLanguageProfs(): Collection
    {
        return $this->languageProfs;
    }

    public function addLanguageProf(LanguageProf $languageProf): self
    {
        if (!$this->languageProfs->contains($languageProf)) {
            $this->languageProfs[] = $languageProf;
            $languageProf->setUser($this);
        }

        return $this;
    }

    public function removeLanguageProf(LanguageProf $languageProf): self
    {
        if ($this->languageProfs->removeElement($languageProf)) {
            // set the owning side to null (unless already changed)
            if ($languageProf->getUser() === $this) {
                $languageProf->setUser(null);
            }
        }

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    public function setBirthdate(?string $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getSocialMedia(): ?SocialMedia
    {
        return $this->socialMedia;
    }

    public function setSocialMedia(SocialMedia $socialMedia): self
    {
        // set the owning side of the relation if necessary
        if ($socialMedia->getUser() !== $this) {
            $socialMedia->setUser($this);
        }

        $this->socialMedia = $socialMedia;

        return $this;
    }

    public function getCvFilename(): ?string
    {
        return $this->cvFilename;
    }

    public function setCvFilename(?string $cvFilename): self
    {
        $this->cvFilename = $cvFilename;

        return $this;
    }

    public function getCoverLetterFilename(): ?string
    {
        return $this->coverLetterFilename;
    }

    public function setCoverLetterFilename(?string $coverLetterFilename): self
    {
        $this->coverLetterFilename = $coverLetterFilename;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }



}
