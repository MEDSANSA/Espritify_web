<?php

namespace App\Entity;

use App\Repository\OffreStageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OffrestageRepository::class)]
class Offrestage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    
    #[Assert\NotBlank(allowNull: true,message: "Internship title is required")]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(allowNull: true,message: "Internship description is required")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(allowNull: true,message: "Internship skills are required")]
    private ?string $competance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(allowNull: true,message: "Internship company description is required")]
    private ?string $desc_soc = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(allowNull: true,message: "Internship company name is required")]
    private ?string $nom_soc = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(allowNull: true,message: "Internship type is required")]
    private ?string $type = null;

    #[ORM\OneToMany(targetEntity: DossierStage::class, mappedBy: 'id_offre')]
    private Collection $dossierStages;

    #[ORM\OneToMany(targetEntity: Entretien::class, mappedBy: 'id_stage')]
    private Collection $entretiens;

    public function __construct()
    {
        $this->dossierStages = new ArrayCollection();
        $this->entretiens = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCompetance(): ?string
    {
        return $this->competance;
    }

    public function setCompetance(string $competance): static
    {
        $this->competance = $competance;

        return $this;
    }

    public function getDescSoc(): ?string
    {
        return $this->desc_soc;
    }

    public function setDescSoc(string $desc_soc): static
    {
        $this->desc_soc = $desc_soc;

        return $this;
    }

    public function getNomSoc(): ?string
    {
        return $this->nom_soc;
    }

    public function setNomSoc(string $nom_soc): static
    {
        $this->nom_soc = $nom_soc;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, DossierStage>
     */
    public function getDossierStages(): Collection
    {
        return $this->dossierStages;
    }

    public function addDossierStage(DossierStage $dossierStage): static
    {
        if (!$this->dossierStages->contains($dossierStage)) {
            $this->dossierStages->add($dossierStage);
            $dossierStage->setIdOffre($this);
        }

        return $this;
    }

    public function removeDossierStage(DossierStage $dossierStage): static
    {
        if ($this->dossierStages->removeElement($dossierStage)) {
            // set the owning side to null (unless already changed)
            if ($dossierStage->getIdOffre() === $this) {
                $dossierStage->setIdOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Entretien>
     */
    public function getEntretiens(): Collection
    {
        return $this->entretiens;
    }

    public function addEntretien(Entretien $entretien): static
    {
        if (!$this->entretiens->contains($entretien)) {
            $this->entretiens->add($entretien);
            $entretien->setIdStage($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): static
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getIdStage() === $this) {
                $entretien->setIdStage(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id ;
    }
}