<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column]
    private ?bool $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?int $rate = null;

    #[ORM\ManyToOne(inversedBy: 'cours' )]
      
    #[ORM\JoinColumn(name: 'id_cat', referencedColumnName: 'id')]
    private ?Categorie $id_cat = null;

    #[ORM\OneToMany(targetEntity: ParticipationCours::class, mappedBy: 'id_cours')]
    private Collection $participationCours;

    public function __construct()
    {
        $this->participationCours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getIdCat(): ?Categorie
    {
        return $this->id_cat;
    }

    public function setIdCat(?Categorie $id_cat): static
    {
        $this->id_cat = $id_cat;

        return $this;
    }

    /**
     * @return Collection<int, ParticipationCours>
     */
    public function getParticipationCours(): Collection
    {
        return $this->participationCours;
    }

    public function addParticipationCour(ParticipationCours $participationCour): static
    {
        if (!$this->participationCours->contains($participationCour)) {
            $this->participationCours->add($participationCour);
            $participationCour->setIdCours($this);
        }

        return $this;
    }

    public function removeParticipationCour(ParticipationCours $participationCour): static
    {
        if ($this->participationCours->removeElement($participationCour)) {
            // set the owning side to null (unless already changed)
            if ($participationCour->getIdCours() === $this) {
                $participationCour->setIdCours(null);
            }
        }

        return $this;
    }
}
