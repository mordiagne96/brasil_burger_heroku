<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CatalogueeRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

// #[ORM\Entity(repositoryClass: CatalogueeRepository::class)]
#[ApiResource(
    itemOperations:[
        "get"
    ]
)]
// #[ApiFilter(
//     SearchFilter::class,
//     properties: [
//         "burgers.nom" => SearchFilter::STRATEGY_PARTIAL,
//     ]
// )]
class Cataloguee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    // #[ORM\OneToMany(mappedBy: 'cataloguee', targetEntity: Burger::class)]
    // #[ApiFilter(SearchFilter::class, properties: ['burgers.nom' => SearchFilter::STRATEGY_PARTIAL])]
    private $burgers;

    // #[ORM\OneToMany(mappedBy: 'cataloguee', targetEntity: Menu::class)]
    private $menus;

    public function __construct()
    {
        $this->burgers = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->id = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // /**
    //  * @return Collection<int, Burger>
    //  */
    // public function getBurgers(): Collection
    // {
    //     return $this->burgers;
    // }

    // public function addBurger(Burger $burger): self
    // {
    //     if (!$this->burgers->contains($burger)) {
    //         $this->burgers[] = $burger;
    //         $burger->setCataloguee($this);
    //     }

    //     return $this;
    // }

    // public function removeBurger(Burger $burger): self
    // {
    //     if ($this->burgers->removeElement($burger)) {
    //         // set the owning side to null (unless already changed)
    //         if ($burger->getCataloguee() === $this) {
    //             $burger->setCataloguee(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, Menu>
    //  */
    // public function getMenus(): Collection
    // {
    //     return $this->menus;
    // }

    // public function addMenu(Menu $menu): self
    // {
    //     if (!$this->menus->contains($menu)) {
    //         $this->menus[] = $menu;
    //         $menu->setCataloguee($this);
    //     }

    //     return $this;
    // }

    // public function removeMenu(Menu $menu): self
    // {
    //     if ($this->menus->removeElement($menu)) {
    //         // set the owning side to null (unless already changed)
    //         if ($menu->getCataloguee() === $this) {
    //             $menu->setCataloguee(null);
    //         }
    //     }

    //     return $this;
    // }


    

    /**
     * Get the value of burgers
     */ 
    public function getBurgers()
    {
        return $this->burgers;
    }

    /**
     * Set the value of burgers
     *
     * @return  self
     */ 
    public function setBurgers($burgers)
    {
        $this->burgers = $burgers;

        return $this;
    }

    /**
     * Get the value of menus
     */ 
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * Set the value of menus
     *
     * @return  self
     */ 
    public function setMenus($menus)
    {
        $this->menus = $menus;

        return $this;
    }
}
