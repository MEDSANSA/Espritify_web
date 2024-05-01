<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'quizz')]
#[ORM\Index(name: 'fk_id_question', columns: ['id_question'])]
#[ORM\Entity]
class Quizz
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private $idQuizz;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le sujet de la quizz est obligatoire.')]
    private $sujet;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'La description de la quizz est obligatoire.')]
    private $description;

    #[ORM\ManyToOne(targetEntity: Questions::class)]
    #[ORM\JoinColumn(name: 'id_question', referencedColumnName: 'id_question')]
    #[Assert\NotBlank(message: 'La Question de la quizz est obligatoire.')]
    private $idQuestion;

    public function getIdQuizz(): ?int
    {
        return $this->idQuizz;
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

    public function getIdQuestion(): ?Questions
    {
        return $this->idQuestion;
    }

    public function setIdQuestion(?Questions $idQuestion): static
    {
        $this->idQuestion = $idQuestion;
        return $this;
    }
}