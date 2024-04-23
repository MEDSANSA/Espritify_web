<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
      
    #[ORM\JoinColumn(name: 'id_club', referencedColumnName: 'id')]
    #[Assert\NotBlank]
    private ?Club $id_club;

    #[ORM\Column( type:"string",length: 255, nullable: false, name:'titre')]
    #[Assert\NotBlank( message: 'The title is required.')]
    private ?string $titre;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank( message: 'The description is required.')]
    private ?string $description;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank( message: 'Lieu is required.')]
    private ?string $lieu;

    #[ORM\Column(type: Types::DATE_MUTABLE, name:'date_debut')]
    #[Assert\NotBlank]
    #[Assert\DateTimeInterface]
    private ?\DateTimeInterface $date_debut;

    #[ORM\Column(type: Types::DATE_MUTABLE, name:'date_fin')]
    #[Assert\NotBlank]
    #[Assert\Expression(
        "(this.getDateFin() >= this.getDateDebut())",
        message : "La date de fin doit être postérieure à la date de début"
    )]
    #[Assert\DateTimeInterface]
    private ?\DateTimeInterface $date_fin;

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
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

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
