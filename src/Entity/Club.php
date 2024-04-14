<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    private ?string $intitule;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $logo;

    #[ORM\Column(length: 255, name:'emailClub')]
    #[Assert\NotBlank( message: 'The email is required.')]
    #[Assert\Email(
            message: 'The email {{ value }} is not a valid email.',
        )]
    private ?string $emailClub;

    #[ORM\Column(length: 255, name:'pageFb')]
    #[Assert\NotBlank]
    private ?string $pageFb;

    #[ORM\Column(length: 255, name:'pageInsta')]
    private ?string $pageInsta;

    #[ORM\OneToMany(targetEntity: Evenement::class, mappedBy: 'id_club')]
    private Collection $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getEmailClub(): ?string
    {
        return $this->emailClub;
    }

    public function setEmailClub(string $emailClub): static
    {
        $this->emailClub = $emailClub;

        return $this;
    }

    public function getPageFb(): ?string
    {
        return $this->pageFb;
    }

    public function setPageFb(string $pageFb): static
    {
        $this->pageFb = $pageFb;

        return $this;
    }

    public function getPageInsta(): ?string
    {
        return $this->pageInsta;
    }

    public function setPageInsta(string $pageInsta): static
    {
        $this->pageInsta = $pageInsta;

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->setIdClub($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getIdClub() === $this) {
                $evenement->setIdClub(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->intitule ?? ''; // Return the 'intitule' property or an empty string if it's null
    }
}
