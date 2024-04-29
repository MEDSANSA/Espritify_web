<?php

namespace App\Entity;

use App\Repository\ParticipationEvenementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationEvenementRepository::class)]
class ParticipationEvenement
{

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'participationEvenement')]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?Utilisateur $id_user = null;
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'participationEvenements')]
      
    #[ORM\JoinColumn(name: 'id_evenement', referencedColumnName: 'id')]
    private ?Evenement $id_evenement = null;

    public function getIdUser(): ?Utilisateur
    {
        return $this->id_user;
    }

    public function setIdUser(?Utilisateur $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdEvenement(): ?Evenement
    {
        return $this->id_evenement;
    }

    public function setIdEvenement(?Evenement $id_evenement): static
    {
        $this->id_evenement = $id_evenement;

        return $this;
    }
}
