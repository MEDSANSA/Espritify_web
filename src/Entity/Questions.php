<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Questions
 *
 * @ORM\Table(name="questions")
 * @ORM\Entity
 */
class Questions
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_question", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idQuestion;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Le contenu de la question est obligatoire.")
     */
    private $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="rep1", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La reponse de la question est obligatoire.")
     */
    private $rep1;

    /**
     * @var string
     *
     * @ORM\Column(name="rep2", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La reponse de la question est obligatoire.")
     */
    private $rep2;

    /**
     * @var string
     *
     * @ORM\Column(name="rep3", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La reponse de la question est obligatoire.")
     */
    private $rep3;

    /**
     * @var string
     *
     * @ORM\Column(name="bon_rep", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La reponse de la question est obligatoire.")
     */
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

    public function __toString()
    {
        return $this->contenu;
    }

}
