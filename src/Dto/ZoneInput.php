<?php

namespace App\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

final class ZoneInput
{
    /**
     * @var string
     */
    #[Assert\NotNull(message: "Le nom est Obligatoire")]
    public $nom;
    
    /**
    * @var int
    */
    #[Assert\Type(
        type: 'integer',
        message: 'Cette valeur {{ value }} est n\'est pas accespter a ce {{ type }}.'
    )]
    public $prix;

    /**
    * @var ArrayCollection<QuartierInput>()
    */
    #[Assert\NotNull(message: "Quartier obligatoire")]
    #[Assert\Count(
        min: 1,
        minMessage: "Ajouter au moins un quartier"
    )]
    public  $quartiers;
}