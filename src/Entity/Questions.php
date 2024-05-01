<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'questions')]
#[ORM\Entity]
class Questions
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $idQuestion;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le contenu de la question est obligatoire.')]
    private $contenu;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'La reponse de la question est obligatoire.')]
    private $rep1;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'La reponse de la question est obligatoire.')]
    private $rep2;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'La reponse de la question est obligatoire.')]
    private $rep3;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'La reponse de la question est obligatoire.')]
    private $bonRep;

    public function getIdQuestion(): ?int
    {
        return $this->idQuestion;
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

    public function getRep1(): ?string
    {
        return $this->rep1;
    }

    public function setRep1(string $rep1): static
    {
        $this->rep1 = $rep1;
        return $this;
    }

    public function getRep2(): ?string
    {
        return $this->rep2;
    }

    public function setRep2(string $rep2): static
    {
        $this->rep2 = $rep2;
        return $this;
    }

    public function getRep3(): ?string
    {
        return $this->rep3;
    }

    public function setRep3(string $rep3): static
    {
        $this->rep3 = $rep3;
        return $this;
    }

    public function getBonRep(): ?string
    {
        return $this->bonRep;
    }

    public function setBonRep(string $bonRep): static
    {
        $this->bonRep = $bonRep;
        return $this;
    }

    public function __toString(): string
    {
        return $this->contenu;
    }
}