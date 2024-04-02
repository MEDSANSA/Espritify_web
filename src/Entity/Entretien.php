<?php

namespace App\Entity;

use App\Repository\EntretienRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntretienRepository::class)]
class Entretien
{
    #[ORM\Id]
   

    #[ORM\ManyToOne(inversedBy: 'entretiens')]
      
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?utilisateur $id_user = null;
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'entretiens')]
      
    #[ORM\JoinColumn(name: 'id_stage', referencedColumnName: 'id')]
    private ?offrestage $id_stage = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column]
    private ?bool $etat = null;

    

    public function getIdUser(): ?utilisateur
    {
        return $this->id_user;
    }

    public function setIdUser(?utilisateur $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdStage(): ?offrestage
    {
        return $this->id_stage;
    }

    public function setIdStage(?offrestage $id_stage): static
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
