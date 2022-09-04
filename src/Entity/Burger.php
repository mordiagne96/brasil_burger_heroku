<?php

namespace App\Entity;

use App\Entity\Catalogue;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\BurgerController;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ApiResource(
    attributes: ["pagination_client_items_per_page" => true],
    collectionOperations:[
        "get"=>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['burger:read']],
            "security"=>"is_granted('PUBLIC_ACCESS')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource"
        ],
        "post" => [
            'status' => Response::HTTP_CREATED,
            'denormalization_context' => ['groups' => ['burger:write:simple']],
            'normalization_context' => ['groups' => ['burger:read:simple']],
            "security"=>"is_granted('ROLE_GESTIONNAIRE')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource",
            // 'deserialize' => false,
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],   // "path"=>"/bugers"
        "add" => [
            'method' => 'post',
            "path"=>"/add",
            "controller"=>BurgerController::class,
            "security"=>"is_granted('ROLE_GESTIONNAIRE')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource",
            'denormalization_context' => ['groups' => ['burger:write:simple']],
            'normalization_context' => ['groups' => ['burger:read:simple']],
            'deserialize' => false,
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ]
    ],
    itemOperations:[
        "get"=>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['burger:read:simple']],
            "security"=>"is_granted('PUBLIC_ACCESS')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource"
        ],
        "put"=>[
            "security"=>"is_granted('ROLE_GESTIONNAIRE')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource",
            'deserialize' => false,
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
        ],
        "delete"
    ]    
    ),
    ApiFilter(
        SearchFilter::class,
        properties: [
            "nom" => SearchFilter::STRATEGY_PARTIAL,
            "prix" => SearchFilter::STRATEGY_EXACT
        ]
    )
]
#[ORM\Entity(repositoryClass: BurgerRepository::class)]

class Burger extends Produit
{

    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'burgers')]
    #[ORM\JoinColumn(nullable: false)]
    // #[Groups(["user:read:all"])]
    private $gestionnaire;

    #[ORM\OneToMany(mappedBy: 'burger', targetEntity: BurgerMenu::class)]
    private $burgerMenus;

    // #[ORM\ManyToOne(targetEntity: Cataloguee::class, inversedBy: 'burgers')]
    private $cataloguee;

    #[ORM\OneToMany(mappedBy: 'burger', targetEntity: BurgerCommande::class)]
    private $burgerCommandes;


    public function __construct()
    {
        $this->burgerMenus = new ArrayCollection();
        $this->burgerCommandes = new ArrayCollection();
    }

    public function getGestionnaire(): ?Gestionnaire
    {
        return $this->gestionnaire;
    }

    public function setGestionnaire(?Gestionnaire $gestionnaire): self
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * @return Collection<int, BurgerMenu>
     */
    public function getBurgerMenus(): Collection
    {
        return $this->burgerMenus;
    }

    public function addBurgerMenu(BurgerMenu $burgerMenu): self
    {
        if (!$this->burgerMenus->contains($burgerMenu)) {
            $this->burgerMenus[] = $burgerMenu;
            $burgerMenu->setBurger($this);
        }

        return $this;
    }

    public function removeBurgerMenu(BurgerMenu $burgerMenu): self
    {
        if ($this->burgerMenus->removeElement($burgerMenu)) {
            // set the owning side to null (unless already changed)
            if ($burgerMenu->getBurger() === $this) {
                $burgerMenu->setBurger(null);
            }
        }

        return $this;
    }

    public function getCataloguee(): ?Cataloguee
    {
        return $this->cataloguee;
    }

    public function setCataloguee(?Cataloguee $cataloguee): self
    {
        $this->cataloguee = $cataloguee;

        return $this;
    }

    /**
     * @return Collection<int, BurgerCommande>
     */
    public function getBurgerCommandes(): Collection
    {
        return $this->burgerCommandes;
    }

    public function addBurgerCommande(BurgerCommande $burgerCommande): self
    {
        if (!$this->burgerCommandes->contains($burgerCommande)) {
            $this->burgerCommandes[] = $burgerCommande;
            $burgerCommande->setBurger($this);
        }

        return $this;
    }

    public function removeBurgerCommande(BurgerCommande $burgerCommande): self
    {
        if ($this->burgerCommandes->removeElement($burgerCommande)) {
            // set the owning side to null (unless already changed)
            if ($burgerCommande->getBurger() === $this) {
                $burgerCommande->setBurger(null);
            }
        }

        return $this;
    }

}
