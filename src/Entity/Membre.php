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

    public function __construct()
    {
        $this->setRoles(['ROLE_MEMBRE']);
        $this->commandes = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
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
}
