<?php

namespace App\Entity;

use App\Dto\CommandeInput;
use App\Dto\CommandeOutput;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\CommandeController;
use App\Repository\CommandeRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
    #[ApiResource(
        collectionOperations:[
            "get"=>[
                'method' => 'get',
                'status' => Response::HTTP_OK,
                'normalization_context' => ['groups' => ['com:read']],
                'denormalization_context' => ['groups' => ['com:write']],
            ],
            "post"=>[
                'status' => Response::HTTP_CREATED,
                'denormalization_context' => ['groups' => ['com:write']],
                'normalization_context' => ['groups' => ['com:read']],
            ],
            "add_stock"=>[
                'method' => 'post',
                "path"=>"/addCommande",
                // "validate"=>false,
                "controller"=>CommandeController::class,
                'status' => Response::HTTP_CREATED,
                'denormalization_context' => ['groups' => ['com:write']],
                'normalization_context' => ['groups' => ['com:read']],
            ]
        ],
        itemOperations:[
            "get" =>[
                'method' => 'get',
                'status' => Response::HTTP_OK,
                'normalization_context' => ['groups' => ['com:read:simple']]
            ],
            "update_Etat" =>[
                'method' => 'put',
                "path"=>"/updateEtat/{id}/{etat}",
                // "validate"=>true,
                "controller"=>UpdateController::class,
                // 'status' => Response::HTTP_CREATED,
                'denormalization_context' => ['groups' => ['com:update:etat']],
                'normalization_context' => ['groups' => ['com:update:read']]
            ]
        ]
        ),   
    ]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['com:read', 'com:read:simple','com:update:etat','livraison:write','livraison:read','com:update:etat','com:update:read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['com:read','com:read:simple','livraison:read','livraison:write','com:update:read'])]
    private $numeroCommande;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['com:read','com:read:simple','livraison:read','com:update:read'])]
    private $date;

    #[ORM\Column(type: 'integer')]
    #[Groups(['com:read','com:read:simple','livraison:read'])]
    private $montant;

    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: true)]
    private $livraison;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['com:read','com:read:simple','livraison:read'])]
    private $client;

    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'commandes')]
    private $gestionnaire;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: TailleBoissonCommande::class, cascade:['persist'])]
    #[Groups(['com:write','com:read:simple'])]
    private $tailleBoissonCommandes;

    #[ORM\ManyToOne(targetEntity: Quartier::class, inversedBy: 'commandes')]
    #[Groups(['com:write','com:read:simple'])]
    private $quartier;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: BurgerCommande::class,  cascade:['persist'])]
    #[Groups(['com:write','com:read:simple'])]
    #[Valid()]
    private $burgerCommandes;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups(['com:update:etat','com:update:etat'])]
    private $etat = "en cours";

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: MenuCommandeTailleBoisson::class, cascade:["persist"])]
    #[Groups(['com:write','com:read:simple'])]
    #[Valid()]
    private $menuCommandeTailleBoissons;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: PortionFriteCommande::class,cascade:["persist"] )]
    #[Groups(['com:write','com:read:simple'])]
    #[Valid()]
    private $portionFriteCommandes;

    public function __construct()
    {
        $this->produitCommandes = new ArrayCollection();
        $this->tailleBoissonCommandes = new ArrayCollection();
        $this->burgerCommandes = new ArrayCollection();
        $this->menuCommandeTailleBoissons = new ArrayCollection();
        $this->portionFriteCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCommande(): ?string
    {
        return $this->numeroCommande;
    }

    public function setNumeroCommande(string $numeroCommande): self
    {
        $this->numeroCommande = $numeroCommande;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
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
     * @return Collection<int, TailleBoissonCommande>
     */
    public function getTailleBoissonCommandes(): Collection
    {
        return $this->tailleBoissonCommandes;
    }

    public function addTailleBoissonCommande(TailleBoissonCommande $tailleBoissonCommande): self
    {
        if (!$this->tailleBoissonCommandes->contains($tailleBoissonCommande)) {
            $this->tailleBoissonCommandes[] = $tailleBoissonCommande;
            $tailleBoissonCommande->setCommande($this);
        }

        return $this;
    }

    public function removeTailleBoissonCommande(TailleBoissonCommande $tailleBoissonCommande): self
    {
        if ($this->tailleBoissonCommandes->removeElement($tailleBoissonCommande)) {
            // set the owning side to null (unless already changed)
            if ($tailleBoissonCommande->getCommande() === $this) {
                $tailleBoissonCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getQuartier(): ?Quartier
    {
        return $this->quartier;
    }

    public function setQuartier(?Quartier $quartier): self
    {
        $this->quartier = $quartier;

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
            $burgerCommande->setCommande($this);
        }

        return $this;
    }

    public function removeBurgerCommande(BurgerCommande $burgerCommande): self
    {
        if ($this->burgerCommandes->removeElement($burgerCommande)) {
            // set the owning side to null (unless already changed)
            if ($burgerCommande->getCommande() === $this) {
                $burgerCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

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
            $menuCommandeTailleBoisson->setCommande($this);
        }

        return $this;
    }

    public function removeMenuCommandeTailleBoisson(MenuCommandeTailleBoisson $menuCommandeTailleBoisson): self
    {
        if ($this->menuCommandeTailleBoissons->removeElement($menuCommandeTailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($menuCommandeTailleBoisson->getCommande() === $this) {
                $menuCommandeTailleBoisson->setCommande(null);
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
            $portionFriteCommande->setCommande($this);
        }

        return $this;
    }

    public function removePortionFriteCommande(PortionFriteCommande $portionFriteCommande): self
    {
        if ($this->portionFriteCommandes->removeElement($portionFriteCommande)) {
            // set the owning side to null (unless already changed)
            if ($portionFriteCommande->getCommande() === $this) {
                $portionFriteCommande->setCommande(null);
            }
        }

        return $this;
    }

    #[Assert\Callback]
    public function validatArray(ExecutionContextInterface $context)
    {
        // dd("callback");
        // dd(count($this->burgerCommandes));
        if(count($this->burgerCommandes) == 0 && count($this->menuCommandeTailleBoissons) == 0){
            $context->buildViolation('Erreur!! Veuillez choisir au moins un burger ou un menu!!')
                        ->addViolation();
        }
    }

    #[Assert\Callback]
    public function doublonsBurger(ExecutionContextInterface $context)
    {

        if(count($this->getBurgerCommandes())  > 1){

            foreach ($this->getBurgerCommandes() as $search) {
                $cpt = 0;
                foreach ($this->getBurgerCommandes() as $value) {
                    if($search->getBurger()->getId() == $value->getBurger()->getId()){
                        $cpt++;
                    }
                }

                if($cpt == 2){
                        $context->buildViolation('Erreur!! Un Burger a été repeter plusieurs fois')
                        ->addViolation();
                    break;
                }
            }
        }
    }


    #[Assert\Callback]
    public function doublonsMenu(ExecutionContextInterface $context)
    {

        if(count($this->getMenuCommandeTailleBoissons())  > 1){

            foreach ($this->getMenuCommandeTailleBoissons() as $search) {
                $cpt = 0;
                foreach ($this->getMenuCommandeTailleBoissons() as $value) {
                    if($search->getMenu()->getId() == $value->getMenu()->getId()){
                        $cpt++;
                    }
                }

                if($cpt == 2){
                        $context->buildViolation('Erreur!! Un menu a été répéter plusieurs fois!!')
                        ->addViolation();
                    break;
                }
            }
        }
    }


    #[Assert\Callback]
    public function boissonMenu(ExecutionContextInterface $context)
    {

        if(count($this->getMenuCommandeTailleBoissons())  > 0){

            foreach ($this->getMenuCommandeTailleBoissons() as $search) {

                if(count($search->getTailleBoissons()) == 0){
                    $context->buildViolation('Erreur!! Veuillez choisir les tailles-boissons du menu')
                        ->addViolation();
                }else{
                    foreach ($search->getTailleBoissons() as $tailleBoisson) {
                        // dd($tailleBoisson->getBoisson());
                        if($tailleBoisson->getBoisson()->getId() == null || $tailleBoisson->getTaille()->getId() == null){
                            $context->buildViolation("Erreur!! Veuillez choisir une taille et un boisson")
                                ->addViolation();
                        } 
                    }
                }
            }
        }
    }

}
