<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["user" => "User", "client" => "Client",  "gestionnaire" => "Gestionnaire", "livreur" => "Livreur"])]
#[ApiResource]
#[UniqueEntity(fields: ['login'], message: 'There is already an account with this login')]
#[UniqueEntity(fields: ['login'], message: 'There is already an account with this login')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["user:read:simple",'user:read:all','com:read:simple','com:read','livraison:read','livraison:write'])]
    protected $id;

    #[Assert\Email(
        message: 'Cette email {{ value }} n\'est pas valid .',
    )]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["user:read:simple",'user:read:all','gest:write','com:read:simple','com:read','livreur:write','livreur:read','livraison:read'])]
    protected $login;

    #[ORM\Column(type: 'json',)]
    #[Groups(["user:read:simple",'user:read:all'])]
    protected $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(['gest:write','livreur:write'])]
    #[Assert\Regex(
        pattern: "/^(?=.*[a-z])(?=.*\d).{6,}$/i",
        message:"Le mot passe est invalide"
    )]
    #[Assert\NotNull(message: "Le mot de passe est Obligatoire!!!")]
    #[Assert\NotBlank(message: "Le mot de passe est Obligatoire!!!")]
    protected $password;

    #[Groups(['gest:write','livreur:write'])]
    #[Assert\NotNull(message: "Confirmer votre mot de passe !!!")]
    #[Assert\NotBlank(message: "Confirmer votre mot de passe!!!")]
    protected $confirmPassword;

    #[ORM\Column(type: 'string', length: 30, nullable: false)]
    #[Assert\NotNull(message: "Le nom est Obligatoire!!!")]
    #[Groups(["user:read:simple",'user:read:all','gest:write','com:read:simple', 'com:read','livreur:write','livreur:read','livraison:read'])]
    protected $nom;

    #[Groups(["user:read:simple",'user:read:all','gest:write','com:read:simple', 'com:read','livreur:write','livreur:read','livraison:read'])]
    #[ORM\Column(type: 'string', length: 30)]
    #[Assert\NotNull(message: "Le prenom est Obligatoire!!!")]
    protected $prenom;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }


    /**
     * Get the value of confirmPassword
     */ 
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * Set the value of confirmPassword
     *
     * @return  self
     */ 
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    #[Assert\Callback]
    public function validatePassword(ExecutionContextInterface $context)
    {
        if($this->getPassword() != $this->getConfirmPassword()){
            $context->buildViolation('Les Mots de passe ne correspondent pas!')
                        ->addViolation();
        }
    }
}
