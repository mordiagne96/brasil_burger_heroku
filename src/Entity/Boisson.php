<?php

namespace App\Entity;

use App\Entity\Taille;
use App\Entity\Produit;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BoissonRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BoissonRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get"=>[
            "security"=>"is_granted('PUBLIC_ACCESS')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource",
            "normalization_context" => ['groups' => ['boisson:simple']]
        ],
        "post"=>[
            "security"=>"is_granted('ROLE_GESTIONNAIRE')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource",
            "normalization_context" => ['groups' => ['boisson:read']]
        ]
        
    ],
    itemOperations:[
        "get"=>[
            "security"=>"is_granted('PUBLIC_ACCESS')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource",
            "normalization_context" => ['groups' => ['boisson:simple']]

        ],
        "put"=>[
            "security"=>"is_granted('ROLE_GESTIONNAIRE')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource",
            "denormalization_context" => ['groups' => ['boisson:write']],
            "normalization_context" => ['groups' => ['boisson:read']]
        ]
    ]
)]
class Boisson extends Produit
{
    #[ORM\OneToMany(mappedBy: 'boisson', targetEntity: TailleBoisson::class)]
    private $tailleBoissons;


    public function __construct()
    {
        parent::__construct();
        $this->tailleBoissons = new ArrayCollection();
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
            $tailleBoisson->setBoisson($this);
        }

        return $this;
    }

    public function removeTailleBoisson(TailleBoisson $tailleBoisson): self
    {
        if ($this->tailleBoissons->removeElement($tailleBoisson)) {
            // set the owning side to null (unless already changed)
            if ($tailleBoisson->getBoisson() === $this) {
                $tailleBoisson->setBoisson(null);
            }
        }
        return $this;
    }

}
