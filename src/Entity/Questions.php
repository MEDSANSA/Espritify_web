<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_question = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(length: 255)]
    private ?string $rep1 = null;

    #[ORM\Column(length: 255)]
    private ?string $rep2 = null;

    #[ORM\Column(length: 255)]
    private ?string $rep3 = null;

    #[ORM\Column(length: 255, name:'bonneReponse')]
    private ?string $bonneReponse = null;

    public function getId(): ?int
    {
        return $this->id_question;
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

    public function getBonneReponse(): ?string
    {
        return $this->bonneReponse;
    }

    public function setBonneReponse(string $bonneReponse): static
    {
        $this->bonneReponse = $bonneReponse;

        return $this;
    }
}
