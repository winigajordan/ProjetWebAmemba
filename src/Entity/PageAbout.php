<?php

namespace App\Entity;

use App\Repository\PageAboutRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageAboutRepository::class)]
class PageAbout
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $missionTitre;

    #[ORM\Column(type: 'text')]
    private $missionText;

    #[ORM\Column(type: 'string', length: 255)]
    private $motTitre;

    #[ORM\Column(type: 'text')]
    private $motContenu;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $missionPath;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $motPath;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMissionTitre(): ?string
    {
        return $this->missionTitre;
    }

    public function setMissionTitre(string $missionTitre): self
    {
        $this->missionTitre = $missionTitre;

        return $this;
    }

    public function getMissionText(): ?string
    {
        return $this->missionText;
    }

    public function setMissionText(string $missionText): self
    {
        $this->missionText = $missionText;

        return $this;
    }

    public function getMotTitre(): ?string
    {
        return $this->motTitre;
    }

    public function setMotTitre(string $motTitre): self
    {
        $this->motTitre = $motTitre;

        return $this;
    }

    public function getMotContenu(): ?string
    {
        return $this->motContenu;
    }

    public function setMotContenu(string $motContenu): self
    {
        $this->motContenu = $motContenu;

        return $this;
    }

    public function getMissionPath(): ?string
    {
        return $this->missionPath;
    }

    public function setMissionPath(?string $missionPath): self
    {
        $this->missionPath = $missionPath;

        return $this;
    }

    public function getMotPath(): ?string
    {
        return $this->motPath;
    }

    public function setMotPath(?string $motPath): self
    {
        $this->motPath = $motPath;

        return $this;
    }
}
