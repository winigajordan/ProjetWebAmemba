<?php

namespace App\Entity;

use App\Repository\MembreBureauRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembreBureauRepository::class)]
class MembreBureau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nomComplet;


    #[ORM\ManyToOne(targetEntity: PostesBureau::class, inversedBy: 'membresBureau')]
    #[ORM\JoinColumn(nullable: false)]
    private $fonction;

    #[ORM\Column(type: 'boolean')]
    private $etat;


    #[ORM\OneToOne(targetEntity: Membre::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $membre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;
        return $this;
    }


    public function getFonction(): ?PostesBureau
    {
        return $this->fonction;
    }

    public function setFonction(?PostesBureau $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getMembre(): ?membre
    {
        return $this->membre;
    }

    public function setMembre(membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }
}
