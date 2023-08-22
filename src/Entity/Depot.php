<?php

namespace App\Entity;

use App\Repository\DepotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepotRepository::class)]
class Depot extends Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $reference;

    #[ORM\Column(type: 'string', length: 255)]
    private $etat;

    #[ORM\Column(type: 'string', length: 255)]
    private $numero;

    #[ORM\Column(type: 'string', length: 255)]
    private $moyen;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getMoyen(): ?string
    {
        return $this->moyen;
    }

    public function setMoyen(string $moyen): self
    {
        $this->moyen = $moyen;

        return $this;
    }
}
