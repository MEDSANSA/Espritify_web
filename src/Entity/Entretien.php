<?php

namespace App\Entity;

use App\Repository\EntretienRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: EntretienRepository::class)]
class Entretien
{
    #[ORM\Id]
   

    #[ORM\ManyToOne(inversedBy: 'entretiens')]
      
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
 
    private ?Utilisateur $id_user = null;
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'entretiens')]
      
    #[ORM\JoinColumn(name: 'id_stage', referencedColumnName: 'id')]
    private ?Offrestage $id_stage = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Interview type is required")]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Interview description is required")]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "Internship date is required")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Interview place is required")]
    private ?string $lieu = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Interview state is required")]
    private ?bool $etat = null;

    

    public function getIdUser(): ?Utilisateur
    {
        return $this->id_user;
    }

    public function setIdUser(?Utilisateur $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdStage(): ?Offrestage
    {
        return $this->id_stage;
    }

    public function setIdStage(?Offrestage $id_stage): static
    {
        $this->id_stage = $id_stage;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

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
}
