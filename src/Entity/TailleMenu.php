<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TailleMenuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TailleMenuRepository::class)]
#[ApiResource()]
class TailleMenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['Menu:write'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'tailleMenus')]
    private $menu;

    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'tailleMenus')]
    #[Groups(['Menu:write'])]
    private $taille;

    #[ORM\Column(type: 'integer')]
    #[Groups(['Menu:write'])]
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getTaille(): ?Taille
    {
        return $this->taille;
    }

    public function setTaille(?Taille $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}
