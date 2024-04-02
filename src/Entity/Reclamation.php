<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
      
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?Utilisateur $id_user = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,options: ['default' => 'CURRENT_TIMESTAMP', 'columnDefinition' => 'date DEFAULT CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\OneToMany(targetEntity: ReponseRec::class, mappedBy: 'id_rec')]
    private Collection $reponseRecs;

    public function __construct()
    {
        $this->reponseRecs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->id_user;
    }

    public function setIdUser(?Utilisateur $id_user): static
    {
        $this->id_user = $id_user;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, ReponseRec>
     */
    public function getReponseRecs(): Collection
    {
        return $this->reponseRecs;
    }

    public function addReponseRec(ReponseRec $reponseRec): static
    {
        if (!$this->reponseRecs->contains($reponseRec)) {
            $this->reponseRecs->add($reponseRec);
            $reponseRec->setIdRec($this);
        }

        return $this;
    }

    public function removeReponseRec(ReponseRec $reponseRec): static
    {
        if ($this->reponseRecs->removeElement($reponseRec)) {
            // set the owning side to null (unless already changed)
            if ($reponseRec->getIdRec() === $this) {
                $reponseRec->setIdRec(null);
            }
        }

        return $this;
    }
}
