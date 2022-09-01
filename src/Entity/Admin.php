<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    public function __construct()
    {
        $this->setRoles(['ROLE_ADMIN']);
    }

    public function getId(): ?int
    {
        return parent::getId();
    }
}
