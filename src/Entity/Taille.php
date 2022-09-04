<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TailleRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TailleRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get"=>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['taille:read:simple']]
        ],
        "post"
    ],
    itemOperations:[
        "get"=>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['taille:read:simple','taille:read:all']]
        ],
        "put"
    ]
)]
class Taille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['Menu:write', 'boisson:write','taille:read','com:write','com:read:simple'])]
    private $id;

    #[Assert\Regex(
        pattern: '/^[0-9]*$/i',
        message:"Le prix est invalide"
    )]
    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(["complement:read:all",'taille:read:simple','Menu:write', 'boisson:write','taille:read','com:write','com:read:simple'])]
    private $prix;

    #[Assert\NotNull(message: "Le prix est Obligatoire!!!")]
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[Groups(["complement:read:all",'taille:read:simple','Menu:write', 'boisson:write','taille:read','com:write','com:read:simple'])]
    private $libelle;

    // #[ORM\ManyToMany(targetEntity: Boisson::class, mappedBy: 'tailles')]
    // #[Groups(['taille:read:simple'])]  
    // private $boissons;

    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: TailleBoisson::class)]
    private $tailleBoissons;

    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: TailleMenu::class)]
    private $tailleMenus;

    // #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'tailles')]
    // private $menu;


    public function __construct()
    {
        // $this->boissons = new ArrayCollection();
        $this->tailleBoissons = new ArrayCollection();
        $this->tailleMenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

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
            $tailleBoisson->setTaille($this);
        }

        return $this;
    }

    public function removeTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        if ($this->tailleBoissons->removeElement($tailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($tailleBoisson->getTaille() === $this) {
                $tailleBoisson->setTaille(null);
            }
        }

        return $this;
    }

    // public function getMenu(): ?Menu
    // {
    //     return $this->menu;
    // }

    // public function setMenu(?Menu $menu): self
    // {
    //     $this->menu = $menu;

    //     return $this;
    // }

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
            $tailleMenu->setTaille($this);
        }

        return $this;
    }

    public function removeTailleMenu(TailleMenu $tailleMenu): self
    {
        if ($this->tailleMenus->removeElement($tailleMenu)) {
            // set the owning side to null (unless already changed)
            if ($tailleMenu->getTaille() === $this) {
                $tailleMenu->setTaille(null);
            }
        }

        return $this;
    }

}
