<?php

namespace App\Dto;

use App\Entity\Zone;
use App\Dto\ZoneInput;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints as Assert;


final class QuartierInput
{
    /**
     * @var string
     */
    #[Assert\NotNull(message: "Le libelle est Obligatoire")]
    public $libelle;

    // /**
    //  * @var ZoneInput
    //  */
    // #[Assert\NotNull(message: "Zone Obligatoire" )]
    // public $zone;
}