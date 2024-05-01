<?php

namespace App\Entity;

use App\Repository\ParticipationCoursRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationCoursRepository::class)]
class ParticipationCours
{
    #[ORM\Id]
   
    #[ORM\ManyToOne(inversedBy: 'participationCours')]
      
    #[ORM\JoinColumn(name: 'id_cours', referencedColumnName: 'id')]
    private ?Cours $id_cours = null;
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'participationCours')]
      
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id')]
    private ?utilisateur $id_utilisateur = null;

  

    public function getIdCours(): ?Cours
    {
        return $this->id_cours;
    }

    public function setIdCours(?Cours $id_cours): static
    {
        $this->id_cours = $id_cours;

        return $this;
    }

    public function getIdUtilisateur(): ?utilisateur
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(?utilisateur $id_utilisateur): static
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }
}