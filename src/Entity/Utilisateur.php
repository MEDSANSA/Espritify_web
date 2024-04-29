<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $email = null;

    #[ORM\Column(length: 255 ,nullable:true)]
    private ?string $mdp = null;

    #[ORM\Column(nullable:true)]
    private ?int $tel = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $rank = null;

    #[ORM\Column(nullable:true)]
    private ?int $score = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $nom_societe = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $niveau = null;

    #[ORM\Column(type: "role_enum", nullable:true)]
    private ?string $role = null;

    #[ORM\OneToMany(targetEntity: Certification::class, mappedBy: 'id_user')]
    private Collection $certifications;

    #[ORM\OneToMany(targetEntity: Conversation::class, mappedBy: 'id_user')]
    private Collection $conversations;

    #[ORM\OneToMany(targetEntity: DossierStage::class, mappedBy: 'id_user')]
    private Collection $dossierStages;

    #[ORM\OneToMany(targetEntity: Entretien::class, mappedBy: 'id_user')]
    private Collection $entretiens;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'id_user')]
    private Collection $messages;

    #[ORM\OneToMany(targetEntity: ParticipationCours::class, mappedBy: 'id_utilisateur')]
    private Collection $participationCours;

    #[ORM\OneToMany(targetEntity: ParticipationEvenement::class, mappedBy: 'id_user')]
    private Collection $participationEvenement;

    #[ORM\OneToMany(targetEntity: PasswordResetToken::class, mappedBy: 'user_id')]
    private Collection $passwordResetTokens;

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'id_user')]
    private Collection $reclamations;

    public function __construct()
    {
        $this->certifications = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->dossierStages = new ArrayCollection();
        $this->entretiens = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->participationCours = new ArrayCollection();
        $this->participationEvenement = new ArrayCollection();
        $this->passwordResetTokens = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getRank(): ?string
    {
        return $this->rank;
    }

    public function setRank(string $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getNomSociete(): ?string
    {
        return $this->nom_societe;
    }

    public function setNomSociete(string $nom_societe): static
    {
        $this->nom_societe = $nom_societe;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

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
            $certification->setIdUser($this);
        }

        return $this;
    }

    public function removeCertification(Certification $certification): static
    {
        if ($this->certifications->removeElement($certification)) {
            // set the owning side to null (unless already changed)
            if ($certification->getIdUser() === $this) {
                $certification->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): static
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->setIdUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): static
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getIdUser() === $this) {
                $conversation->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DossierStage>
     */
    public function getDossierStages(): Collection
    {
        return $this->dossierStages;
    }

    public function addDossierStage(DossierStage $dossierStage): static
    {
        if (!$this->dossierStages->contains($dossierStage)) {
            $this->dossierStages->add($dossierStage);
            $dossierStage->setIdUser($this);
        }

        return $this;
    }

    public function removeDossierStage(DossierStage $dossierStage): static
    {
        if ($this->dossierStages->removeElement($dossierStage)) {
            // set the owning side to null (unless already changed)
            if ($dossierStage->getIdUser() === $this) {
                $dossierStage->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Entretien>
     */
    public function getEntretiens(): Collection
    {
        return $this->entretiens;
    }

    public function addEntretien(Entretien $entretien): static
    {
        if (!$this->entretiens->contains($entretien)) {
            $this->entretiens->add($entretien);
            $entretien->setIdUser($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): static
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getIdUser() === $this) {
                $entretien->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setIdUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getIdUser() === $this) {
                $message->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ParticipationCours>
     */
    public function getParticipationCours(): Collection
    {
        return $this->participationCours;
    }

    public function addParticipationCour(ParticipationCours $participationCour): static
    {
        if (!$this->participationCours->contains($participationCour)) {
            $this->participationCours->add($participationCour);
            $participationCour->setIdUtilisateur($this);
        }

        return $this;
    }

    public function removeParticipationCour(ParticipationCours $participationCour): static
    {
        if ($this->participationCours->removeElement($participationCour)) {
            // set the owning side to null (unless already changed)
            if ($participationCour->getIdUtilisateur() === $this) {
                $participationCour->setIdUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ParticipationEvenement>
     */
    public function getParticipationEvenement(): Collection
    {
        return $this->participationEvenement;
    }

    public function addparticipationEvenement(ParticipationEvenement $participationEvenement): static
    {
        if (!$this->participationEvenement->contains($participationEvenement)) {
            $this->participationEvenement->add($participationEvenement);
            $participationEvenement->setIdUser($this);
        }

        return $this;
    }

    public function removeparticipationEvenement(ParticipationEvenement $participationEvenement): static
    {
        if ($this->participationEvenement->removeElement($participationEvenement)) {
            // set the owning side to null (unless already changed)
            if (participationEvenement->getIdUser() === $this) {
                participationEvenement->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PasswordResetToken>
     */
    public function getPasswordResetTokens(): Collection
    {
        return $this->passwordResetTokens;
    }

    public function addPasswordResetToken(PasswordResetToken $passwordResetToken): static
    {
        if (!$this->passwordResetTokens->contains($passwordResetToken)) {
            $this->passwordResetTokens->add($passwordResetToken);
            $passwordResetToken->setUserId($this);
        }

        return $this;
    }

    public function removePasswordResetToken(PasswordResetToken $passwordResetToken): static
    {
        if ($this->passwordResetTokens->removeElement($passwordResetToken)) {
            // set the owning side to null (unless already changed)
            if ($passwordResetToken->getUserId() === $this) {
                $passwordResetToken->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setIdUser($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getIdUser() === $this) {
                $reclamation->setIdUser(null);
            }
        }

        return $this;
    }
}
