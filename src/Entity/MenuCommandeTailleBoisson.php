<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\MenuCommandeTailleBoissonRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: MenuCommandeTailleBoissonRepository::class)]
#[ApiResource()]
class MenuCommandeTailleBoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['com:write','com:read','com:read:simple'])]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(['com:write','com:read','com:read:simple'])]
    #[Assert\Positive(message: "La quantité doit etre positive")]
    private $quantite;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuCommandeTailleBoissons')]
    #[Groups(['com:write','com:read','com:read:simple'])]
    #[Assert\NotNull(message:"Veuillez Entrer un menu")]
    private $menu;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'menuCommandeTailleBoissons')]
    // #[Groups(['com:write'])]
    private $commande;

    #[ORM\OneToMany(mappedBy: 'menuCommandeTailleBoisson', targetEntity: TailleBoisson::class,cascade:["persist"])]
    #[Groups(['com:write','com:read','com:read:simple'])]
    #[Valid()]
    private $tailleBoissons;

    public function __construct()
    {
        $this->tailleBoissons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

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

    public function getTailleBoisson(): ?TailleBoisson
    {
        return $this->tailleBoisson;
    }

    public function setTailleBoisson(?TailleBoisson $tailleBoisson): self
    {
        $this->tailleBoisson = $tailleBoisson;

        return $this;
    }

    /**
     * @return Collection<int, TailleBoisson>
     */
    public function getTailleBoissons(): Collection
    {
        return $this->tailleBoissons;
    }

    public function addTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        if (!$this->tailleBoissons->contains($tailleBoisson)) {
            $this->tailleBoissons[] = $tailleBoisson;
            $tailleBoisson->setMenuCommandeTailleBoisson($this);
        }

        return $this;
    }

    public function removeTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        if ($this->tailleBoissons->removeElement($tailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($tailleBoisson->getMenuCommandeTailleBoisson() === $this) {
                $tailleBoisson->setMenuCommandeTailleBoisson(null);
            }
        }

        return $this;
    }
}
