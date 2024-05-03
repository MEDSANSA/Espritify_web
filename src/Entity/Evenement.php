<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
      
    #[ORM\JoinColumn(name: 'id_club', referencedColumnName: 'id')]
    private ?Club $id_club = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, name:'dateDebut')]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, name:'dateFin')]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\OneToMany(targetEntity: ParticipationEvenement::class, mappedBy: 'id_evenement')]
    private Collection $participationEvenements;

    public function __construct()
    {
        $this->participationEvenements = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdClub(): ?Club
    {
        return $this->id_club;
    }

    public function setIdClub(?Club $id_club): static
    {
        $this->id_club = $id_club;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection<int, ParticipationEvenement>
     */
    public function getParticipationEvenements(): Collection
    {
        return $this->participationEvenements;
    }

    public function addParticipationEvenement(ParticipationEvenement $participationEvenement): static
    {
        if (!$this->participationEvenements->contains($participationEvenement)) {
            $this->participationEvenements->add($participationEvenement);
            $participationEvenement->setIdEvenement($this);
        }

        return $this;
    }

    public function removeParticipationEvenement(ParticipationEvenement $participationEvenement): static
    {
        if ($this->participationEvenements->removeElement($participationEvenement)) {
            // set the owning side to null (unless already changed)
            if ($participationEvenement->getIdEvenement() === $this) {
                $participationEvenement->setIdEvenement(null);
            }
        }

        return $this;
    }
}
