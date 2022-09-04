<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\RegisterController;
use App\Controller\RegistrationController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(
    collectionOperations:[
        "get"=>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ["user:simple"]],
            // 'normalization_context' => ['groups' => ['user:read:simple']],
            // "security"=>"is_granted('ROLE_CLIENT')",
            // "security_message"=>"Vous n'avez pas d'accés à cette Ressource"
            "security"=>"is_granted('ROLE_GESTIONNAIRE')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource"
        ],
        "post"=>[
            "security"=>"is_granted('IS_AUTHENTICATED_ANONYMOUSLY')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource"
        ],
        "post_register" => [
            "method"=>"post",
            'path'=>'/addClient',
            "controller"=>RegisterController::class,
            'normalization_context' => ['groups' => ['user:read:simple']],
            "security"=>"is_granted('PUBLIC_ACCESS')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource"
            ]
    ],
    itemOperations:[
        "get"=>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['user:read:all']],
            "security"=>"is_granted('ROLE_GESTIONNAIRE')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource"
        ],
        "put"=>[
            "security"=>"is_granted('ROLE_GESTIONNAIRE') or is_granted('ROLE_CLIENT')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource"
        ]
    ]
)]
class Client extends User
{
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[Groups(['user:read:simple','user:read:all','com:read:simple','com:read','livraison:read'])]
    private $adresse;

    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(['user:read:simple','user:read:all','com:read:simple','com:read','livraison:read'])]
    private $telephone;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Commande::class)]
    private $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
            }
        }

        return $this;
    }
}
