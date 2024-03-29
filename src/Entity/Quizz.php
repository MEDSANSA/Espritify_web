<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Quizz
 *
 * @ORM\Table(name="quizz", indexes={@ORM\Index(name="fk_id_question", columns={"id_question"})})
 * @ORM\Entity
 */
class Quizz
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_quizz", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idQuizz;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Le sujet de la quizz est obligatoire.")
     */
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La description de la quizz est obligatoire.")
     */
    private $description;

    /**
     * @var \Questions
     *
     * @ORM\ManyToOne(targetEntity="Questions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_question", referencedColumnName="id_question")
     * })
     * @Assert\NotBlank(message="La Question de la quizz est obligatoire.")
     */
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
