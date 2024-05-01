<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'cours')]
#[ORM\Index(name: 'fk_cat', columns: ['id_cat'])]
#[ORM\Entity]
class Cours
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire.')]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le contenu est obligatoire.')]
    private $contenu;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotBlank(message: "L'etat est obligatoire.")]
    private $etat;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: 'Le rate est obligatoire.')]
    private $rate;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(name: 'id_cat', referencedColumnName: 'id')]
    #[Assert\NotBlank(message: 'La categorie est obligatoire.')]
    private $idCat;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
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

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;
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
        return $this->idCat;
    }

    public function setIdCat(?Categorie $idCat): static
    {
        $this->idCat = $idCat;
        return $this;
    }
}