<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $promotion;

    #[ORM\Column(type: 'string', length: 255)]
    private $pays;

    #[ORM\Column(type: 'string', length: 255)]
    private $ville;

    #[ORM\Column(type: 'string', length: 255)]
    private $telephone;

    #[ORM\Column(type: 'boolean')]
    private $statut;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $cv;

    #[ORM\OneToOne(mappedBy: 'membre', targetEntity: Wallet::class, cascade: ['persist', 'remove'])]
    private $wallet;

    #[ORM\OneToMany(mappedBy: 'proprietaire', targetEntity: Entreprise::class)]
    private $entreprises;

    #[ORM\OneToMany(mappedBy: 'membre', targetEntity: OffreEmplois::class)]
    private $offreEmplois;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $profile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $profession;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $secteur;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $entreprise;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $roleAmicale;

    #[ORM\Column(type: 'string', length: 25, nullable: true)]
    private $bac;

    #[ORM\Column(type: 'text', nullable: true)]
    private $univ;

    #[ORM\Column(type: 'text', nullable: true)]
    private $diplome;

    #[ORM\Column(type: 'text', nullable: true)]
    private $experience;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $link;

    #[ORM\Column(type: 'text', nullable: true)]
    private $bio;

    
    #[ORM\ManyToMany(targetEntity: Cotisation::class, mappedBy: 'contributeurs')]
    private $cotisations;

    public function __construct()
    {
        $this->setRoles(['ROLE_MEMBRE']);
        $this->commandes = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
        $this->offreEmplois = new ArrayCollection();
        $this->cotisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return parent::getId();
    }

    public function getPromotion(): ?string
    {
        return $this->promotion;
    }

    public function setPromotion(string $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        // unset the owning side of the relation if necessary
        if ($wallet === null && $this->wallet !== null) {
            $this->wallet->setMembre(null);
        }

        // set the owning side of the relation if necessary
        if ($wallet !== null && $wallet->getMembre() !== $this) {
            $wallet->setMembre($this);
        }

        $this->wallet = $wallet;

        return $this;
    }

    /**
     * @return Collection<int, Entreprise>
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprise $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises[] = $entreprise;
            $entreprise->setProprietaire($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprise $entreprise): self
    {
        if ($this->entreprises->removeElement($entreprise)) {
            // set the owning side to null (unless already changed)
            if ($entreprise->getProprietaire() === $this) {
                $entreprise->setProprietaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OffreEmplois>
     */
    public function getOffreEmplois(): Collection
    {
        return $this->offreEmplois;
    }

    public function addOffreEmploi(OffreEmplois $offreEmploi): self
    {
        if (!$this->offreEmplois->contains($offreEmploi)) {
            $this->offreEmplois[] = $offreEmploi;
            $offreEmploi->setMembre($this);
        }

        return $this;
    }

    public function removeOffreEmploi(OffreEmplois $offreEmploi): self
    {
        if ($this->offreEmplois->removeElement($offreEmploi)) {
            // set the owning side to null (unless already changed)
            if ($offreEmploi->getMembre() === $this) {
                $offreEmploi->setMembre(null);
            }
        }

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): self
    {
        $this->profession = $profession;

        return $this;
    }

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(?string $secteur): self
    {
        $this->secteur = $secteur;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(?string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getRoleAmicale(): ?string
    {
        return $this->roleAmicale;
    }

    public function setRoleAmicale(?string $roleAmicale): self
    {
        $this->roleAmicale = $roleAmicale;

        return $this;
    }

    public function getBac(): ?string
    {
        return $this->bac;
    }

    public function setBac(?string $bac): self
    {
        $this->bac = $bac;

        return $this;
    }

    public function getUniv(): ?string
    {
        return $this->univ;
    }

    public function setUniv(?string $univ): self
    {
        $this->univ = $univ;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(?string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * @return Collection<int, Cotisation>
     */
    public function getCotisations(): Collection
    {
        return $this->cotisations;
    }

    public function addCotisation(Cotisation $cotisation): self
    {
        if (!$this->cotisations->contains($cotisation)) {
            $this->cotisations[] = $cotisation;
            $cotisation->addContributeur($this);
        }

        return $this;
    }

    public function removeCotisation(Cotisation $cotisation): self
    {
        if ($this->cotisations->removeElement($cotisation)) {
            $cotisation->removeContributeur($this);
        }

        return $this;
    }
}
