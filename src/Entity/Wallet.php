<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float')]
    private $solde;

    #[ORM\OneToOne(inversedBy: 'wallet', targetEntity: Membre::class, cascade: ['persist', 'remove'])]
    private $membre;

    #[ORM\OneToOne(mappedBy: 'wallet', targetEntity: Transaction::class, cascade: ['persist', 'remove'])]
    private $transaction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(Transaction $transaction): self
    {
        // set the owning side of the relation if necessary
        if ($transaction->getWallet() !== $this) {
            $transaction->setWallet($this);
        }

        $this->transaction = $transaction;

        return $this;
    }
}
