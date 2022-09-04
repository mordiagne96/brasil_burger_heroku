<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BurgerCommandeRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BurgerCommandeRepository::class)]
#[ApiResource()]
class BurgerCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['com:write','com:read:simple'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Burger::class, inversedBy: 'burgerCommandes')]
    #[Groups(['com:write','com:read:simple'])]
    #[Assert\NotNull(message:"Entrer un Burger SVP!!!")]
    private $burger;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'burgerCommandes')]
    #[Groups(['com:write'])]
    private $commande;

    #[ORM\Column(type: 'integer')]
    #[Groups(['com:write','com:read:simple'])]
    #[Assert\Positive(message:"La quantité doit etre positive")]
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

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
