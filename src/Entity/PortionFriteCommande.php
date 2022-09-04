<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PortionFriteCommandeRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PortionFriteCommandeRepository::class)]
#[ApiResource()]
class PortionFriteCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['com:write','com:read','com:read:simple'])]
    private $id;

    #[ORM\ManyToOne(targetEntity: PortionFrite::class, inversedBy: 'portionFriteCommandes')]
    #[Groups(['com:write','com:read','com:read:simple'])]
    #[Assert\NotNull(message:"Portion Obligatoire")]
    private $portionFrite;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'portionFriteCommandes')]
    private $commande;

    #[ORM\Column(type: 'integer')]
    #[Groups(['com:write','com:read','com:read:simple'])]
    #[Assert\Positive(message:"La quantité doit etre positive")]
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPortionFrite(): ?PortionFrite
    {
        return $this->portionFrite;
    }

    public function setPortionFrite(?PortionFrite $portionFrite): self
    {
        $this->portionFrite = $portionFrite;

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
