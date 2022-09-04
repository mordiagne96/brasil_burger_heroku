<?php

namespace App\Entity;

use App\Dto\PortionFriteInput;
use App\Dto\PortionFriteOutput;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PortionFriteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: PortionFriteRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get",
        "post"=>[
            'denormalization_context' => ['groups' => ['write']],   
        ]
    ]
)]
class PortionFrite extends Produit
{
    #[ORM\OneToMany(mappedBy: 'portionFrite', targetEntity: Menu::class)]
    private $menus;

    #[ORM\OneToMany(mappedBy: 'portionFrite', targetEntity: PortionFriteCommande::class)]
    private $portionFriteCommandes;

    public function __construct()
    {
        parent::__construct();
        $this->menus = new ArrayCollection();
        $this->portionFriteCommandes = new ArrayCollection();
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->setPortionFrite($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getPortionFrite() === $this) {
                $menu->setPortionFrite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PortionFriteCommande>
     */
    public function getPortionFriteCommandes(): Collection
    {
        return $this->portionFriteCommandes;
    }

    public function addPortionFriteCommande(PortionFriteCommande $portionFriteCommande): self
    {
        if (!$this->portionFriteCommandes->contains($portionFriteCommande)) {
            $this->portionFriteCommandes[] = $portionFriteCommande;
            $portionFriteCommande->setPortionFrite($this);
        }

        return $this;
    }

    public function removePortionFriteCommande(PortionFriteCommande $portionFriteCommande): self
    {
        if ($this->portionFriteCommandes->removeElement($portionFriteCommande)) {
            // set the owning side to null (unless already changed)
            if ($portionFriteCommande->getPortionFrite() === $this) {
                $portionFriteCommande->setPortionFrite(null);
            }
        }

        return $this;
    }
}
