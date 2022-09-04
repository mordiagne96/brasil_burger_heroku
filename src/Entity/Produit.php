<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ApiResource()]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["produit" => "Produit", "menu" => "Menu",  "burger" => "Burger", "portion_frite" => "PortionFrite",  "boisson" => "Boisson"])]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["simple","burger:read",'portion:read:simple','complement:read:all','Menu:write','Menu:read', 'boisson:simple','taille:read','com:write','com:read:simple'])]
    protected $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotNull(message: "Le nom est Obligatoire!!!")]
    #[Assert\NotBlank(message:"Le nom est Obligatoire")]
    // #[Groups(['write'])]
    #[Groups(["simple","burger:read",'burger:read:simple','portion:read:simple','complement:read:all','Menu:write','boisson:write','write:simple','burger:write:simple','boisson:simple','Menu:read','taille:read','com:write','com:read:simple'])]
    protected $nom;

    // #[Assert\NotNull(message: "Le prix est Obligatoire!!!")]
    // #[Assert\Positive(message: "Le prix ne doit pas etre négatiif")]
    #[Groups(["simple","burger:read",'burger:read:simple','write:simple','write:all', 'Menu:read', 'boisson:simple','taille:read','burger:write:simple'])]
    #[ORM\Column(type: 'integer', nullable: true)]
    // #[Groups(['write'])]
    
    private $prix;


    // #[ORM\Column(type: 'integer')]
    // #[Groups(['Menu:read','burger:read:simple', 'boisson:write', 'boisson:simple','taille:read'])]
    // private $etat;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Groups(['Menu:read','burger:read:simple', 'boisson:write', 'boisson:simple','taille:read'])]
    private $etat;

    #[ORM\Column(type: 'blob', nullable: true)]
    #[Groups(['burger:read','burger:read:simple','simple','taille:read','Menu:read'])]
    private $image;

     /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="filePath")
     */
    #[Groups(['Menu:write','burger:write:simple'])]
    #[Assert\NotNull(message: "L'image est Obligatoire!!!")]
    public ?File $file = null;

    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'produits')]
    private $gestionnaire;
     
    public function __construct()
    {
        $this->produitCommandes = new ArrayCollection();
        $this->setEtat(0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    public function getImage(){
        return is_resource($this->image) ? base64_encode(stream_get_contents($this->image)) : $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

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
     * Get the value of file
     */ 
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
