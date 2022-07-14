<?php

namespace App\Entity;

use App\Repository\PostesBureauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostesBureauRepository::class)]
class PostesBureau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $libelle;

    #[ORM\OneToMany(mappedBy: 'fonction', targetEntity: MembreBureau::class, orphanRemoval: true)]
    private $membresBureau;

    public function __construct()
    {
        $this->membresBureau = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, MembreBureau>
     */
    public function getMembresBureau(): Collection
    {
        return $this->membresBureau;
    }

    public function addMembresBureau(MembreBureau $membresBureau): self
    {
        if (!$this->membresBureau->contains($membresBureau)) {
            $this->membresBureau[] = $membresBureau;
            $membresBureau->setFonction($this);
        }

        return $this;
    }

    public function removeMembresBureau(MembreBureau $membresBureau): self
    {
        if ($this->membresBureau->removeElement($membresBureau)) {
            // set the owning side to null (unless already changed)
            if ($membresBureau->getFonction() === $this) {
                $membresBureau->setFonction(null);
            }
        }

        return $this;
    }
}
