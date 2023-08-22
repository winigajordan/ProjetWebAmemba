<?php

namespace App\Entity;

use App\Repository\PageAccueilRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageAccueilRepository::class)]
class PageAccueil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $carouselTitre1;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $carouselTitre2;

    #[ORM\Column(type: 'string', length: 255)]
    private $carouselTitre3;

    #[ORM\Column(type: 'text', nullable: true)]
    private $carouselText1;

    #[ORM\Column(type: 'text', nullable: true)]
    private $carouselText2;

    #[ORM\Column(type: 'text', nullable: true)]
    private $carouselText3;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $carouselImage1;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $carouselImage2;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $carouselImage3;

    #[ORM\Column(type: 'string', length: 255)]
    private $missionTitre;

    #[ORM\Column(type: 'text')]
    private $missionText;

    #[ORM\Column(type: 'string', length: 255)]
    private $chiffreAlumni;

    #[ORM\Column(type: 'string', length: 255)]
    private $chiffreProjet;

    #[ORM\Column(type: 'string', length: 255)]
    private $chiffreFonds;

    #[ORM\Column(type: 'string', length: 255)]
    private $chiffreAlumniText;

    #[ORM\Column(type: 'string', length: 255)]
    private $chiffreProjetText;

    #[ORM\Column(type: 'string', length: 255)]
    private $chiffreFondsText;

    #[ORM\Column(type: 'string', length: 255)]
    private $entrepriseTitre;

    #[ORM\Column(type: 'text')]
    private $entrepriseTexte;

    #[ORM\Column(type: 'string', length: 255)]
    private $temoignageAuteur1; // titre

    #[ORM\Column(type: 'string', length: 255)]
    private $temoignageAuteur2; //titre 

    #[ORM\Column(type: 'string', length: 255)]
    private $temoignageAuteur3; //non titre

    #[ORM\Column(type: 'string', length: 255)]
    private $temoignageAuteur4; //non titre

    #[ORM\Column(type: 'text')]
    private $temoignageTitre1;

    #[ORM\Column(type: 'text')]
    private $temoignageTitre2;

    #[ORM\Column(type: 'text')]
    private $temoignageText1;

    #[ORM\Column(type: 'text')]
    private $temoignageText2;

    #[ORM\Column(type: 'text')]
    private $temoignageText4;

    #[ORM\Column(type: 'text')]
    private $temoignageText3;

    #[ORM\Column(type: 'string', length: 255)]
    private $membreTitre;

    #[ORM\Column(type: 'text')]
    private $membreText;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $missionImg;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $ancienneImg1;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $ancienneImg2;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $ancienneImg3;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $blogTitre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $blogText = null;


   

    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarouselTitre1(): ?string
    {
        return $this->carouselTitre1;
    }

    public function setCarouselTitre1(?string $carouselTitre1): self
    {
        $this->carouselTitre1 = $carouselTitre1;

        return $this;
    }

    public function getCarouselTitre2(): ?string
    {
        return $this->carouselTitre2;
    }

    public function setCarouselTitre2(?string $carouselTitre2): self
    {
        $this->carouselTitre2 = $carouselTitre2;

        return $this;
    }

    public function getCarouselTitre3(): ?string
    {
        return $this->carouselTitre3;
    }

    public function setCarouselTitre3(string $carouselTitre3): self
    {
        $this->carouselTitre3 = $carouselTitre3;

        return $this;
    }

    public function getCarouselText1(): ?string
    {
        return $this->carouselText1;
    }

    public function setCarouselText1(?string $carouselText1): self
    {
        $this->carouselText1 = $carouselText1;

        return $this;
    }

    public function getCarouselText2(): ?string
    {
        return $this->carouselText2;
    }

    public function setCarouselText2(?string $carouselText2): self
    {
        $this->carouselText2 = $carouselText2;

        return $this;
    }

    public function getCarouselText3(): ?string
    {
        return $this->carouselText3;
    }

    public function setCarouselText3(?string $carouselText3): self
    {
        $this->carouselText3 = $carouselText3;

        return $this;
    }

    public function getCarouselImage1(): ?string
    {
        return $this->carouselImage1;
    }

    public function setCarouselImage1(?string $carouselImage1): self
    {
        $this->carouselImage1 = $carouselImage1;

        return $this;
    }

    public function getCarouselImage2(): ?string
    {
        return $this->carouselImage2;
    }

    public function setCarouselImage2(?string $carouselImage2): self
    {
        $this->carouselImage2 = $carouselImage2;

        return $this;
    }

    public function getCarouselImage3(): ?string
    {
        return $this->carouselImage3;
    }

    public function setCarouselImage3(?string $carouselImage3): self
    {
        $this->carouselImage3 = $carouselImage3;

        return $this;
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

    public function getChiffreAlumni(): ?string
    {
        return $this->chiffreAlumni;
    }

    public function setChiffreAlumni(string $chiffreAlumni): self
    {
        $this->chiffreAlumni = $chiffreAlumni;

        return $this;
    }

    public function getChiffreProjet(): ?string
    {
        return $this->chiffreProjet;
    }

    public function setChiffreProjet(string $chiffreProjet): self
    {
        $this->chiffreProjet = $chiffreProjet;

        return $this;
    }

    public function getChiffreFonds(): ?string
    {
        return $this->chiffreFonds;
    }

    public function setChiffreFonds(string $chiffreFonds): self
    {
        $this->chiffreFonds = $chiffreFonds;

        return $this;
    }

    public function getChiffreAlumniText(): ?string
    {
        return $this->chiffreAlumniText;
    }

    public function setChiffreAlumniText(string $chiffreAlumniText): self
    {
        $this->chiffreAlumniText = $chiffreAlumniText;

        return $this;
    }

    public function getChiffreProjetText(): ?string
    {
        return $this->chiffreProjetText;
    }

    public function setChiffreProjetText(string $chiffreProjetText): self
    {
        $this->chiffreProjetText = $chiffreProjetText;

        return $this;
    }

    public function getChiffreFondsText(): ?string
    {
        return $this->chiffreFondsText;
    }

    public function setChiffreFondsText(string $chiffreFondsText): self
    {
        $this->chiffreFondsText = $chiffreFondsText;

        return $this;
    }

    public function getEntrepriseTitre(): ?string
    {
        return $this->entrepriseTitre;
    }

    public function setEntrepriseTitre(string $entrepriseTitre): self
    {
        $this->entrepriseTitre = $entrepriseTitre;

        return $this;
    }

    public function getEntrepriseTexte(): ?string
    {
        return $this->entrepriseTexte;
    }

    public function setEntrepriseTexte(string $entrepriseTexte): self
    {
        $this->entrepriseTexte = $entrepriseTexte;

        return $this;
    }

    public function getTemoignageAuteur1(): ?string
    {
        return $this->temoignageAuteur1;
    }

    public function setTemoignageAuteur1(string $temoignageAuteur1): self
    {
        $this->temoignageAuteur1 = $temoignageAuteur1;

        return $this;
    }

    public function getTemoignageAuteur2(): ?string
    {
        return $this->temoignageAuteur2;
    }

    public function setTemoignageAuteur2(string $temoignageAuteur2): self
    {
        $this->temoignageAuteur2 = $temoignageAuteur2;

        return $this;
    }

    public function getTemoignageAuteur3(): ?string
    {
        return $this->temoignageAuteur3;
    }

    public function setTemoignageAuteur3(string $temoignageAuteur3): self
    {
        $this->temoignageAuteur3 = $temoignageAuteur3;

        return $this;
    }

    public function getTemoignageAuteur4(): ?string
    {
        return $this->temoignageAuteur4;
    }

    public function setTemoignageAuteur4(string $temoignageAuteur4): self
    {
        $this->temoignageAuteur4 = $temoignageAuteur4;

        return $this;
    }

    public function getTemoignageTitre1(): ?string
    {
        return $this->temoignageTitre1;
    }

    public function setTemoignageTitre1(string $temoignageTitre1): self
    {
        $this->temoignageTitre1 = $temoignageTitre1;

        return $this;
    }

    public function getTemoignageTitre2(): ?string
    {
        return $this->temoignageTitre2;
    }

    public function setTemoignageTitre2(string $temoignageTitre2): self
    {
        $this->temoignageTitre2 = $temoignageTitre2;

        return $this;
    }

    public function getTemoignageText1(): ?string
    {
        return $this->temoignageText1;
    }

    public function setTemoignageText1(string $temoignageText1): self
    {
        $this->temoignageText1 = $temoignageText1;

        return $this;
    }

    public function getTemoignageText2(): ?string
    {
        return $this->temoignageText2;
    }

    public function setTemoignageText2(string $temoignageText2): self
    {
        $this->temoignageText2 = $temoignageText2;

        return $this;
    }

    public function getTemoignageText4(): ?string
    {
        return $this->temoignageText4;
    }

    public function setTemoignageText4(string $temoignageText4): self
    {
        $this->temoignageText4 = $temoignageText4;

        return $this;
    }

    public function getTemoignageText3(): ?string
    {
        return $this->temoignageText3;
    }

    public function setTemoignageText3(string $temoignageText3): self
    {
        $this->temoignageText3 = $temoignageText3;

        return $this;
    }

    public function getMembreTitre(): ?string
    {
        return $this->membreTitre;
    }

    public function setMembreTitre(string $membreTitre): self
    {
        $this->membreTitre = $membreTitre;

        return $this;
    }

    public function getMembreText(): ?string
    {
        return $this->membreText;
    }

    public function setMembreText(string $membreText): self
    {
        $this->membreText = $membreText;

        return $this;
    }

    public function getMissionImg(): ?string
    {
        return $this->missionImg;
    }

    public function setMissionImg(?string $missionImg): self
    {
        $this->missionImg = $missionImg;

        return $this;
    }

    public function getAncienneImg1(): ?string
    {
        return $this->ancienneImg1;
    }

    public function setAncienneImg1(?string $ancienneImg1): self
    {
        $this->ancienneImg1 = $ancienneImg1;

        return $this;
    }

    public function getAncienneImg2(): ?string
    {
        return $this->ancienneImg2;
    }

    public function setAncienneImg2(?string $ancienneImg2): self
    {
        $this->ancienneImg2 = $ancienneImg2;

        return $this;
    }

    public function getAncienneImg3(): ?string
    {
        return $this->ancienneImg3;
    }

    public function setAncienneImg3(?string $ancienneImg3): self
    {
        $this->ancienneImg3 = $ancienneImg3;

        return $this;
    }

    public function getBlogTitre(): ?string
    {
        return $this->blogTitre;
    }

    public function setBlogTitre(?string $blogTitre): self
    {
        $this->blogTitre = $blogTitre;

        return $this;
    }

    public function getBlogText(): ?string
    {
        return $this->blogText;
    }

    public function setBlogText(?string $blogText): self
    {
        $this->blogText = $blogText;

        return $this;
    }

   



}
