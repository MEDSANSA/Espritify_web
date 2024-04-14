<?php

namespace App\Entity;

use App\Repository\DossierStageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DossierStageRepository::class)]
class DossierStage
{
    #[ORM\Id]
   

    #[ORM\ManyToOne(inversedBy: 'dossierStages')]
      
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?Utilisateur $id_user = null;
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'dossierStages')]
      
    #[ORM\JoinColumn(name: 'id_offre', referencedColumnName: 'id')]
    private ?Offrestage $id_offre = null;

    #[ORM\Column(length: 255, nullable:true)]
    #[Assert\NotBlank(message: "CV file is required")]
    private ?string $cv = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Internship convention file is required")]
    private ?string $convention = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "ID card copy file is required")]
    private ?string $copie_cin = null;

    
    public function getIdUser(): ?Utilisateur
    {
        return $this->id_user;
    }

    public function setIdUser(?Utilisateur $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdOffre(): ?offrestage
    {
        return $this->id_offre;
    }

    public function setIdOffre(?offrestage $id_offre): static
    {
        $this->id_offre = $id_offre;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getConvention(): ?string
    {
        return $this->convention;
    }

    public function setConvention(string $convention): static
    {
        $this->convention = $convention;

        return $this;
    }

    public function getCopieCin(): ?string
    {
        return $this->copie_cin;
    }

    public function setCopieCin(string $copie_cin): static
    {
        $this->copie_cin = $copie_cin;

        return $this;
    }
}
