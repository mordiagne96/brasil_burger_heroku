<?php

namespace App\Entity;

use App\Dto\MenuInput;
use App\Dto\MenuOutput;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use App\Services\PortionExisteService;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Annotations\Annotation\Attribute;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PortionFriteRepository;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => [
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['Menu:read:simple']],
            "security" => "is_granted('PUBLIC_ACCESS')",
            "security_message" => "Vous n'avez pas d'accés à cette Ressource"
        ],
        "post" => [
            'normalization_context' => ['groups' => ['Menu:read']],
            'denormalization_context' => ['groups' => ['Menu:write']],
            'input_formats' => [
                'multipart' => ['multipart/form-data'],
            ],
            // 'input' => MenuInput::class,
            // 'output' => MenuOutput::class
        ]
    ],
    itemOperations: [
        "put" => [
            "security" => "is_granted('ROLE_GESTIONNAIRE')",
            "security_message" => "Vous n'avez pas d'accés à cette Ressource"
        ],
        "get" => [
            "security" => "is_granted('PUBLIC_ACCESS')",
            "security_message" => "Vous n'avez pas d'accés à cette Ressource"
        ]
    ]
    // input:MenuInput::class,
    // output:MenuOutput::class,
    // denormalizationContext: ['groups' => ['write']],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        "nom" => SearchFilter::STRATEGY_PARTIAL,
        "prix" => SearchFilter::STRATEGY_EXACT
    ]
)]
class Menu extends Produit
{

    // private PortionExisteService $servicePortion;

    #[ORM\ManyToOne(targetEntity: PortionFrite::class, inversedBy: 'menus')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Menu:write', 'Menu:read'])]
    #[Assert\NotNull(message: "Ajouter une portion de frite")]
    private $portionFrite;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: BurgerMenu::class, cascade: ["persist"])]
    #[Groups(['Menu:write'])]
    #[Assert\Count(
        min: 1,
        minMessage: "Ajouter un Burger"
    )]
    private $burgerMenus;

    // #[ORM\ManyToOne(targetEntity: Cataloguee::class, inversedBy: 'menus')]
    private $cataloguee;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: TailleMenu::class, cascade: ["persist"])]
    #[Groups(['Menu:write',"Menu:read"])]
    #[Assert\Count(
        min: 1,
        minMessage: "Ajouter une Taille"
    )]
    private $tailleMenus;

    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuCommandeTailleBoisson::class)]
    private $menuCommandeTailleBoissons;

    // #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuCommande::class)]
    // private $menuCommandes;

    public function __construct()
    {
        parent::__construct();
        $this->burgerMenus = new ArrayCollection();
        $this->tailleMenus = new ArrayCollection();
        // $this->menuCommandes = new ArrayCollection();
        $this->menuCommandeTailleBoissons = new ArrayCollection();
        // $this->servicePortion = new PortionExisteService();
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
            $burgerMenu->setMenu($this);
        }

        return $this;
    }

    public function removeBurgerMenu(BurgerMenu $burgerMenu): self
    {
        if ($this->burgerMenus->removeElement($burgerMenu)) {
            // set the owning side to null (unless already changed)
            if ($burgerMenu->getMenu() === $this) {
                $burgerMenu->setMenu(null);
            }
        }

        return $this;
    }

    #[Assert\Callback]
    public function doublons(ExecutionContextInterface $context)
    {

        if(count($this->getBurgerMenus())  > 1){

            foreach ($this->getBurgerMenus() as $search) {
                $trouve = false;$cpt = 0;
                foreach ($this->getBurgerMenus() as $value) {
                    if($search->getBurger()->getId() == $value->getBurger()->getId()){
                        $cpt++;
                    }
                }

                if($cpt == 2){
                        $context->buildViolation('Erreur!! verifier les doublons!')
                        ->addViolation();
                    break;
                }
            }
        }
        

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
     * @return Collection<int, TailleMenu>
     */
    public function getTailleMenus(): Collection
    {
        return $this->tailleMenus;
    }

    public function addTailleMenu(TailleMenu $tailleMenu): self
    {
        if (!$this->tailleMenus->contains($tailleMenu)) {
            $this->tailleMenus[] = $tailleMenu;
            $tailleMenu->setMenu($this);
        }

        return $this;
    }

    public function removeTailleMenu(TailleMenu $tailleMenu): self
    {
        if ($this->tailleMenus->removeElement($tailleMenu)) {
            // set the owning side to null (unless already changed)
            if ($tailleMenu->getMenu() === $this) {
                $tailleMenu->setMenu(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, MenuCommandeTailleBoisson>
     */
    public function getMenuCommandeTailleBoissons(): Collection
    {
        return $this->menuCommandeTailleBoissons;
    }

    public function addMenuCommandeTailleBoisson(MenuCommandeTailleBoisson $menuCommandeTailleBoisson): self
    {
        if (!$this->menuCommandeTailleBoissons->contains($menuCommandeTailleBoisson)) {
            $this->menuCommandeTailleBoissons[] = $menuCommandeTailleBoisson;
            $menuCommandeTailleBoisson->setMenu($this);
        }

        return $this;
    }

    public function removeMenuCommandeTailleBoisson(MenuCommandeTailleBoisson $menuCommandeTailleBoisson): self
    {
        if ($this->menuCommandeTailleBoissons->removeElement($menuCommandeTailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($menuCommandeTailleBoisson->getMenu() === $this) {
                $menuCommandeTailleBoisson->setMenu(null);
            }
        }

        return $this;
    }

}
