<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ComplementRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations:[
        "get"=>[
            'method' => 'get',
            'status' => Response::HTTP_OK,
            "security"=>"is_granted('PUBLIC_ACCESS')",
            "security_message"=>"Vous n'avez pas d'accés à cette Ressource"
        ]
    ],
    itemOperations:[
            "get",
    ]
)]
class Complement
{

    private $id;

    private array $taille;

    private array $portionFrite;

   

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of taille
     */ 
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Set the value of taille
     *
     * @return  self
     */ 
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * Get the value of portionFrite
     */ 
    public function getPortionFrite()
    {
        return $this->portionFrite;
    }

    /**
     * Set the value of portionFrite
     *
     * @return  self
     */ 
    public function setPortionFrite($portionFrite)
    {
        $this->portionFrite = $portionFrite;

        return $this;
    }
}
