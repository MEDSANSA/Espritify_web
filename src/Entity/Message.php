<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
      
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?Utilisateur $id_user = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
      
    #[ORM\JoinColumn(name: 'id_conv', referencedColumnName: 'id')]
    private ?Conversation $id_conv = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,  options: ['default' => 'CURRENT_TIMESTAMP', 'columnDefinition' => 'datetime DEFAULT CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdConv(): ?conversation
    {
        return $this->id_conv;
    }

    public function setIdConv(?conversation $id_conv): static
    {
        $this->id_conv = $id_conv;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}