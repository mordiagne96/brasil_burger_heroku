<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\TailleBoisson;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TailleBoissonCommandeRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TailleBoissonCommandeRepository::class)]
#[ApiResource()]
class TailleBoissonCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['com:write','com:read:simple'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: TailleBoisson::class, inversedBy: 'tailleBoissonCommandes')]
    #[Groups(['com:write','com:read:simple'])]
    private $tailleBoisson;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'tailleBoissonCommandes')]
    private $commande;

    #[ORM\Column(type: 'integer')]
    #[Groups(['com:write','com:read:simple'])]
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTailleBoisson(): ?TailleBoisson
    {
        return $this->tailleBoisson;
    }

    public function setTailleBoisson(?TailleBoisson $tailleBoisson): self
    {
        $this->tailleBoisson = $tailleBoisson;

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
