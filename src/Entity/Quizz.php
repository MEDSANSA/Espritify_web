<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
    #[ORM\ManyToOne(inversedBy: 'quizzs')]
    
    #[ORM\JoinColumn(name: 'id_question', referencedColumnName: 'id_question')]
    private ?Questions $id_question = null;

    #[ORM\Column( length: 255)]
    private ?string $sujet = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_quizz = null;

    #[ORM\OneToMany(targetEntity: Certification::class, mappedBy: 'id_quizz')]
    private Collection $certifications;

    public function __construct()
    {
        $this->certifications = new ArrayCollection();
    }

   
    public function getIdQuestion(): ?Questions
    {
        return $this->id_question;
    }

    public function setIdQuestion(?Questions $id_question): static
    {
        $this->id_question = $id_question;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): static
    {
        $this->sujet = $sujet;

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

    public function getIdQuizz(): ?int
    {
        return $this->id_quizz;
    }

    public function setIdQuizz(int $id_quizz): static
    {
        $this->id_quizz = $id_quizz;

        return $this;
    }

    /**
     * @return Collection<int, Certification>
     */
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    public function addCertification(Certification $certification): static
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setIdQuizz($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): static
    {
        if ($this->certifications->removeElement($certification)) {
            // set the owning side to null (unless already changed)
            if ($certification->getIdQuizz() === $this) {
                $certification->setIdQuizz(null);
            }
        }

        return $this;
    }
}
