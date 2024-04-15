<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificationRepository::class)]
class Certification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'certifications')]
    #[ORM\JoinColumn(nullable: false, name:'id_user')]

    private ?utilisateur $id_user = null;

    #[ORM\ManyToOne(inversedBy: 'certifications')]
    
    #[ORM\JoinColumn(name: 'id_quizz', referencedColumnName: 'id_quizz')]
    private ?Quizz $id_quizz = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getIdUser(): ?utilisateur
    {
        return $this->id_user;
    }

    public function setIdUser(?utilisateur $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdQuizz(): ?Quizz
    {
        return $this->id_quizz;
    }

    public function setIdQuizz(?Quizz $id_quizz): static
    {
        $this->id_quizz = $id_quizz;

        return $this;
    }

 
}
